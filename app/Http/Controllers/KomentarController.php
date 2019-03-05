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
        $komentar= new Komentar;
        $komentar->isi=$r->isi;
        $komentar->id_resep=$r->id_resep;
        $komentar->username=$r->username;
        $komentar->tgl=Carbon::now();
        $komentar->save();
        return response()->json([
            'pesan' => 'sukses'  
        ]);
    }

    public function delete_komentar(Request $r)
    {
        $komentar= komentar::findOrFail($r->id_komentar);
        $komentar->delete();
        return response()->json([
            'pesan' => 'sukses'  
        ]);

    }

    public function update_komentar(Request $r)
    {
        $komentar= Komentar::findOrFail($r->id_komentar);
        $komentar->isi=$r->isi;
        $komentar->id_resep=$r->id_resep;
        $komentar->username=$r->username;
        $komentar->tgl=$r->tgl;
        $komentar->save();
        return response()->json([
            'pesan' => 'sukses'  
        ]);

    }

    public function ambil_komentar(Request $r)
    {
        $komentar= Komentar::where(['id_resep'=>$r->id_resep])->get();
        return $komentar;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function show(Komentar $komentar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function edit(Komentar $komentar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Komentar $komentar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Komentar $komentar)
    {
        //
    }
}
