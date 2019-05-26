<?php

namespace App\Http\Controllers;

use App\User;
use App\Koleksi;
use Carbon\Carbon;
use App\ListKoleksi;
use Illuminate\Http\Request;

class KoleksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function koleksi(Request $r)
    {
        // $user = User::where('token_login', $r->token)->first();
        $user = User::where('username', $r->username)->first();
        if (!is_null($user)) {
            return response()->json([
                'pesan' => 'sukses',
                'data' => ListKoleksi::where('username', $user->username)->get()
            ]);
        } else {
            return response()->json([
                'pesan' => 'data user tidak ditemukan'
            ]);
        }
    }

    public function koleksi_detail(Request $r)
    {
        // set format ke bhs indonesia
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); //set defaul timezone 

        $koleksi_resep = Koleksi::where('id_list', $r->id_list)->get();
        $data = [];
        $sekarang = Carbon::now();

        foreach ($koleksi_resep as $resep) {
            $waktu_post = $resep->resep->waktu_post->diffInDays($sekarang) > 2 ?
                $resep->resep->waktu_post->format('d M Y H:i') : $resep->resep->waktu_post->diffForHumans($sekarang);
            array_push($data, [
                'id_resep' => $resep->resep->id_resep,
                'judul' => $resep->resep->judul,
                'foto' => $resep->resep->foto,
                'bahan' => $resep->resep->bahan,
                'langkah' => $resep->resep->langkah,
                'waktu_post' => $waktu_post,
                'kategori' => $resep->resep->kategori->kategori,
                'username' => $resep->resep->username,
                'nama' => $resep->resep->user->nama,
                'like' => $resep->resep->like->count(),
                'komentar' => $resep->resep->komentar->count()
            ]);
        }
        return response()->json([
            'pesan' => 'sukses',
            'data' => $data
        ]);
    }

    public function hapus_koleksi(Request $r)
    {
        $koleksi = ListKoleksi::where('id_list', $r->id_list);
        if ($koleksi->delete()) {
            return response()->json([
                'pesan' => 'sukses'
            ]);
        }
        return response()->json([
            'pesan' => 'gagal'
        ]);
    }

    public function hapus_dari_koleksi(Request $r)
    {
        $id_resep = $r->id_resep;
        $username = $r->username;
        $bookmarked = Koleksi::where('id_resep', $id_resep)->whereHas('list_koleksi', function ($q) use ($username) {
            $q->where('username', $username);
        })->first();
        if ($bookmarked) {
            $bookmarked->delete();
            return response()->json([
                'pesan' => 'sukses',
                'data' => $bookmarked->list_koleksi
            ]);
        } else {
            return response()->json([
                'pesan' => 'gagal'
            ]);
        }
    }

    public function tambah_koleksi(Request $r)
    {
        $koleksi = new ListKoleksi;
        $koleksi->nama_koleksi = $r->nama_koleksi;
        $koleksi->username = $r->username;
        $koleksi->save();
        return response()->json([
            'pesan' => 'sukses'
        ]);
    }

    public function tambah_ke_koleksi(Request $r)
    {
        $koleksi = Koleksi::where([
            'id_resep' => $r->id_resep,
            'id_list' => $r->id_list
        ])->first();
        if (is_null($koleksi)) {
            $koleksi = new Koleksi;
            $koleksi->id_resep = $r->id_resep;
            $koleksi->id_list = $r->id_list;
            $koleksi->save();
        }
        return response()->json([
            'pesan' => 'sukses',
            'data' => ListKoleksi::find($r->id_list)
        ]);
    }
}
