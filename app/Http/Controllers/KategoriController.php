<?php

namespace App\Http\Controllers;

use App\Kategori;
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
        $isi_kategori = Kategori::find($r->id_kategori);
        if (!is_null($isi_kategori)) {
            return response()->json([
                'pesan' => 'sukses',
                'data' => $isi_kategori->resep
            ]);
        } else {
            return response()->json([
                'pesan' => 'gagal',
            ]);
        }
    }
}
