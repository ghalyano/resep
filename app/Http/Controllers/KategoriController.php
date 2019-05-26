<?php

namespace App\Http\Controllers;

use App\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kategori()
    {
        return Kategori::all();
    }

    public function isi_kategori(Request $r)
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone ke indonesia

        $isi_kategori = Kategori::find($r->id_kategori);
        $resep = $isi_kategori->resep;
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

        if (!is_null($isi_kategori)) {
            return response()->json([
                'pesan' => 'sukses',
                'data' => $hasil
            ]);
        } else {
            return response()->json([
                'pesan' => 'gagal',
            ]);
        }
    }
}
