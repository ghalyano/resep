<?php

namespace App\Http\Controllers;

use App\Koleksi;
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
         return Koleksi::all();
    }

    public function hapus_koleksi(Request $r)
    {
        $koleksi= ListKoleksi::findOrFail($r->id_koleksi);
        $koleksi->delete();
        return 'sukses';

    }

    public function hapus_dari_koleksi(Request $r)
    {
        $koleksi= Koleksi::findOrFail($r->id_koleksi);
        $koleksi->delete();
        return 'sukses';

    }

    public function tambah_koleksi(Request $r)
    {
        $koleksi=new ListKoleksi;
        $koleksi->nama_koleksi = $r->nama_koleksi;
        $koleksi->username = $r->username;
        $koleksi->save();
    }

    public function tambah_ke_koleksi (Request $r)
    {
        $koleksi=new Koleksi;
        $koleksi->id_resep=$r->id_resep;
        $koleksi->id_list=$r->id_list;
        $koleksi->save();
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
     * @param  \App\Koleksi  $koleksi
     * @return \Illuminate\Http\Response
     */
    public function show(Koleksi $koleksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Koleksi  $koleksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Koleksi $koleksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Koleksi  $koleksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Koleksi $koleksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Koleksi  $koleksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Koleksi $koleksi)
    {
        //
    }
}
