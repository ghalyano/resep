<?php

namespace App\Http\Controllers;

use App\Komentar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function simpan_komentar(Request $r)
    {
        $komentar = new Komentar;
        $komentar->isi = $r->isi;
        $komentar->id_resep = $r->id_resep;
        $komentar->username = $r->username;
        $komentar->tgl = Carbon::now();
        $komentar->save();
        return response()->json([
            'pesan' => 'sukses',
            'data' => $komentar
        ]);
    }

    public function delete_komentar(Request $r)
    {
        $komentar = Komentar::where([
            'id_komentar' => $r->id_komentar,
            'username' => $r->username
        ])->first();

        if (is_null($komentar)) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'id komentar untuk user ini tidak ditemukan'
            ]);
        }

        $komentar->delete();
        return response()->json([
            'status' => 'sukses'
        ]);
    }

    public function ambil_komentar(Request $r)
    {
        $komentar = Komentar::where(['id_resep' => $r->id_resep])->get();
        return $komentar;
    }
}
