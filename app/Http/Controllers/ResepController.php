<?php

namespace App\Http\Controllers;

use App\Resep;
use Illuminate\Http\Request;
use App\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

        $resep = Resep::orderBy('waktu_post')->limit(10)->get();
        $hasil = [];
        $sekarang = Carbon::now();

        foreach ($resep as $data) {
            $waktu_post = $data->waktu_post->diffInDays($sekarang) > 2 ?
                $data->waktu_post->format('d M Y H:i') : $data->waktu_post->diffForHumans($sekarang);
            array_push($hasil, [
                'id_resep' => $data->id_resep,
                'judul' => $data->judul,
                'foto' => $data->foto,
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

        return response()->json(
            [
                'pesan' => 'sukses',
                'data' => [
                    'id_resep' => $resep->id_resep,
                    'judul' => $resep->judul,
                    'foto' => $resep->foto,
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
                    'is_liked' => $resep->isLiked($r->username)
                ]
            ]
        );
    }

    public function cari_resep(Request $r)
    {
        $resep = Resep::where('judul', 'LIKE', '%' . $r->query . '%')->get();
        return $resep;
    }

    public function update_resep(Request $r)
    {
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
        $resep = Resep::findOrFail($r->id_resep);
        $resep->delete();
        return response()->json([
            'pesan' => 'sukses'
        ]);
    }

    public function buat_resep(Request $r)
    {
        //validasi input
        if (empty($r->judul) && empty($r->foto) && empty($r->bahan) && empty($r->langkah)) {
            return response()->json([
                'pesan' => 'gagal',
                'status' => 'Judul, Foto, Bahan dan Langkah wajib diisi'
            ]);
        }

        /* Log::info("PESAN DARI MOBILE>>" . join(", ", $r->bahan)); // log input ke laravel.log
        exit(); */

        $resep = new Resep;
        $resep->judul = $r->judul;
        $resep->foto = $r->foto == null ? "" : $r->foto;
        // $resep->bahan = $r->bahan; // bahan di simpan di  table sendiri
        // $resep->langkah = $r->langkah; // langkah jg disimpan di table sendiri
        // todo handel upload foto jg belum
        $resep->waktu_post = Carbon::now();
        $resep->id_kategori = $r->id_kategori;
        $resep->username = $r->username;
        $resep->link_video = $r->link_video == null ? "" : $r->link_video;
        $resep->save();
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
