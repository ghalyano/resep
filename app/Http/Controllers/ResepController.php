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
    public function resep(Request $r)
    {
        // set format ke bhs indonesia
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia

        // todo limit later request
        $resep = Resep::orderBy('waktu_post', 'desc')->get();
        $hasil = [];
        $sekarang = Carbon::now();
        foreach ($resep as $data) {
            $waktu_post = $data->waktu_post->diffInDays($sekarang) > 2 ?
                $data->waktu_post->format('d M Y H:i') : $data->waktu_post->diffForHumans($sekarang);
            if ($data->foto != null) {
                $foto = asset('storage') . '/' . $data->foto;
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

        return response()->json($hasil);
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
            $foto = asset('storage') . '/' . $resep->foto;
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
                    'kategori' => $resep->kategori->kategori,
                    'username' => $resep->username,
                    'nama' => $resep->user->nama,
                    'like' => $resep->like->count(),
                    'komentar' => $resep->komentar->count(),
                    'is_liked' => $resep->isLiked($r->username),
                    'is_bookmarked' => $resep->isBookmarked($r->username, $r->id),
                    'link_video' => $resep->link_video
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
                    $foto = asset('storage') . '/' . $data->foto;
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
        $resep = Resep::findOrFail($r->id_resep);
        $resep->judul = $r->judul;
        $resep->foto = $r->foto;
        $resep->bahan = $r->bahan;
        $resep->langkah = $r->langkah;
        $resep->waktu_post = $r->waktu_post;
        $resep->id_kategori = $r->id_kategori;
        $resep->username = $r->username;
        $resep->link_video = $r->link_video;
        $resep->save();
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
        if (empty($r->judul) && empty($r->foto) && empty($r->bahan) && empty($r->langkah)) {
            return response()->json([
                'pesan' => 'gagal',
                'status' => 'Judul, Foto, Bahan dan Langkah wajib diisi'
            ]);
        }

        // * handle upload gambar dulu
        $decoded_img = base64_decode($r->foto);
        // $uploaded_to  = Storage::disk('public')->put('covers', $decoded_img);
        $store_path = storage_path('app' . DIRECTORY_SEPARATOR  . 'public' . DIRECTORY_SEPARATOR  . 'covers' . DIRECTORY_SEPARATOR);
        $name = Str::random() . ".jpg";
        $file_name = $store_path . $name;
        file_put_contents($file_name, $decoded_img);
        $resep = new Resep;
        $resep->judul = $r->judul;
        $resep->foto = 'covers/' . $name;
        $resep->waktu_post = Carbon::now(); // todo handle time to be now
        $resep->id_kategori = $r->id_kategori;
        $resep->username = $r->username;
        $resep->link_video = $r->link_video == null ? "" : $r->link_video;
        $resep->save();

        foreach ($r->bahan as $bhn) {
            $tbl_bahan = new Bahan;
            $tbl_bahan->bahan = $bhn;
            $tbl_bahan->id_resep = $resep->id_resep;
            $tbl_bahan->save();
        }

        foreach ($r->langkah as $lgk) {
            $tbl_langkah = new Langkah;
            $tbl_langkah->langkah = $lgk;
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
