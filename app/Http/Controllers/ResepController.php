<?php

namespace App\Http\Controllers;

use App\Resep;
use Illuminate\Http\Request;
use App\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Like;
use App\Bahan;
use App\Langkah;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resep_terpopuler()
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia
        $resep2 = Resep::with(['bahan', 'langkah', 'kategori:id_kategori,kategori', 'user:username,nama'])
            ->withCount(['like', 'komentar'])
            ->orderBy('like_count', 'desc')
            ->take(10)
            ->get();
        return response()->json($resep2);
    }
    public function resep()
    {
        // set format ke bhs indonesia
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia

        // todo limit later request
        $resep = Resep::with(['bahan', 'langkah', 'kategori:id_kategori,kategori', 'user:username,nama'])
            ->withCount(['like', 'komentar'])
            ->orderBy('waktu_post', 'desc')
            ->take(10)
            ->get();

        return response()->json($resep);
    }

    public function detail_resep(Request $r)
    {
        $resep = Resep::find($r->id);
        if (is_null($resep)) {
            return response()->json([
                'pesan' => 'gagal',
                'data' => 'resep tidak ditemukan'
            ]);
        }

        // set format ke bhs indonesia
        \Carbon\Carbon::setLocale('id');

        if ($resep->foto != null) {
            $foto = $resep->foto;
        } else {
            $foto = '';
        }

        return response()->json(
            [
                'pesan' => 'sukses',
                'data' => [
                    'id_resep' => $resep->id_resep,
                    'judul' => $resep->judul,
                    'foto' => $foto,
                    'bahan' => $resep->bahan,
                    'langkah' => $resep->langkah,
                    'list_komentar' => $resep->komentar,
                    'waktu_post' => $resep->waktu_post->format('d M Y H:i'),
                    'waktu_post_baca' => $resep->waktu_post->diffForHumans(),
                    'id_kategori' => $resep->id_kategori,
                    'kategori' => $resep->kategori->kategori,
                    'username' => $resep->username,
                    'nama' => $resep->user->nama,
                    'like' => $resep->like->count(),
                    'komentar' => $resep->komentar->count(),
                    'is_liked' => $resep->isLiked($r->username),
                    'is_bookmarked' => $resep->isBookmarked($r->username, $r->id),
                    'link_video' => is_null($resep->link_video) ? '' : $resep->link_video,
                    'tips' => is_null($resep->tips) ? '' : $resep->tips
                ]
            ]
        );
    }

    public function cari_resep(Request $r)
    {
        $resep = Resep::where('judul', 'LIKE', '%' . $r->cari . '%')->get();
        if (!is_null($resep)) {
            $hasil = [];
            $sekarang = Carbon::now();
            foreach ($resep as $data) {
                $waktu_post = $data->waktu_post->diffInDays($sekarang) > 2 ?
                    $data->waktu_post->format('d M Y H:i') : $data->waktu_post->diffForHumans($sekarang);
                if ($data->foto != null) {
                    $foto = $data->foto;
                } else {
                    $foto = '';
                }
                array_push($hasil, [
                    'id_resep' => $data->id_resep,
                    'judul' => $data->judul,
                    'foto' => $foto,
                    'bahan' => $data->bahan,
                    'langkah' => $data->langkah,
                    'waktu_post' => $waktu_post,
                    'kategori' => $data->kategori->kategori,
                    'username' => $data->username,
                    'nama' => $data->user->nama,
                    'like' => $data->like->count(),
                    'komentar' => $data->komentar->count()
                ]);
            }
            return response()->json([
                'pesan' => 'sukses',
                'data' => $hasil,
                'jumlah' => $resep->count()
            ]);
        }
        return response()->json([
            'pesan' => 'gagal'
        ]);
    }

    public function update_resep(Request $r)
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia

        //validasi input
        if (empty($r->judul) && empty($r->foto) && empty($r->bahan) && empty($r->langkah)) {
            return 'judul, foto, bahan, dan langkah wajib diisi';
        }

        $resep = Resep::findOrFail($r->id);
        $resep->judul = $r->judul;

        // * handle upload gambar dulu
        if (!is_null($r->foto)) {
            if (!is_null($resep->foto) and $resep->foto == '') {
                Storage::disk('public')->delete($resep->foto);
            }
            $decoded_img = base64_decode($r->foto);
            // $uploaded_to  = Storage::disk('public')->put('covers', $decoded_img);
            $store_path = storage_path('app' . DIRECTORY_SEPARATOR  . 'public' . DIRECTORY_SEPARATOR  . 'covers' . DIRECTORY_SEPARATOR);
            $name = $this->generate_name();
            $file_name = $store_path . $name;
            file_put_contents($file_name, $decoded_img);
            $resep->foto = 'covers/' . $name;
        }

        for ($i = 0; $i < count($r->langkah); $i++) {
            $data = explode('-_-*', $r->langkah[$i]);
            if ($data[0] != 0) {
                $langkah = Langkah::find($data[0]);
                $langkah->langkah = $data[1];
                $langkah->foto = "";
                $langkah->id_resep = $r->id;
                $langkah->save();
            } else {
                $langkah = new Langkah;
                $langkah->langkah = $data[1];
                $langkah->foto = "";
                $langkah->id_resep = $r->id;
                $langkah->save();
            }
        }

        for ($i = 0; $i < count($r->bahan); $i++) {
            $data = explode('-_-*', $r->bahan[$i]);
            if ($data[0] != 0) {
                $bahan = Bahan::find($data[0]);
                $bahan->bahan = $data[1];
                $bahan->id_resep = $r->id;
                $bahan->save();
            } else {
                $bahan = new Bahan;
                $bahan->bahan = $data[1];
                $bahan->id_resep = $r->id;
                $bahan->save();
            }
        }

        if (!is_null($r->hapus_bahan)) {
            foreach ($r->hapus_bahan as $val) {
                if ($val != 0) {
                    $bahan = Bahan::find($val);
                    $bahan->delete();
                }
            }
        }

        if (!is_null($r->hapus_langkah)) {
            foreach ($r->hapus_langkah as $val) {
                if ($val != 0) {
                    $langkah = Langkah::find($val);
                    $langkah->delete();
                }
            }
        }

        $resep->tips = $r->tips;
        $resep->id_kategori = $r->id_kategori;
        $resep->link_video = is_null($r->link_video) ? "" : $r->link_video;
        // info($r);
        $resep->save();
    }

    private function generate_name()
    {
        $nama = Str::random() . ".jpg";
        while (Storage::disk('public')->exists('covers/' . $nama)) {
            $nama = Str::random() . ".jpg";
        }

        return $nama;
    }

    public function delete_resep(Request $r)
    {
        $resep = Resep::where('id_resep', $r->id_resep);
        if ($resep->delete()) {
            return response()->json([
                'pesan' => 'sukses'
            ]);
        } else {
            return response()->json([
                'pesan' => 'gagal'
            ]);
        }
    }

    public function buat_resep(Request $r)
    {
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia
        //validasi input
        if (empty($r->judul) or empty($r->foto) or empty($r->bahan) or empty($r->langkah)) {
            return response()->json([
                'pesan' => 'gagal',
                'status' => 'Judul, Foto, Bahan dan Langkah wajib diisi'
            ]);
        }

        $resep = new Resep;
        $resep->judul = $r->judul;

        // * handle upload gambar dulu
        $decoded_img = base64_decode($r->foto);
        // $uploaded_to  = Storage::disk('public')->put('covers', $decoded_img);
        $store_path = storage_path('app' . DIRECTORY_SEPARATOR  . 'public' . DIRECTORY_SEPARATOR  . 'covers' . DIRECTORY_SEPARATOR);
        $name = Str::random() . ".jpg";
        $file_name = $store_path . $name;
        file_put_contents($file_name, $decoded_img);
        $resep->foto = 'covers/' . $name;

        $resep->waktu_post = Carbon::now();
        $resep->tips = $r->tips;
        $resep->id_kategori = $r->id_kategori;
        $resep->username = $r->username;
        $resep->link_video = $r->link_video == null ? "" : $r->link_video;
        $resep->save();

        for ($i = 0; $i < count($r->bahan); $i++) {
            $tbl_bahan = new Bahan;
            $tbl_bahan->bahan = $r->bahan[$i];
            $tbl_bahan->id_resep = $resep->id_resep;
            $tbl_bahan->save();
        }

        for ($i = 0; $i < count($r->langkah); $i++) {
            $tbl_langkah = new Langkah;
            $tbl_langkah->langkah = $r->langkah[$i];
            $tbl_langkah->id_resep = $resep->id_resep;
            $tbl_langkah->foto = ""; // todo nanti diganti jika bisa upload foto
            $tbl_langkah->save();
        }

        return response()->json([
            'pesan' => 'sukses'
        ]);
    }

    public function resep_saya(Request $r)
    {
        $reseps = Resep::where('username', $r->username)->get();
        return response()->json([
            'status' => 'sukses',
            'data' => $reseps
        ]);
    }
}
