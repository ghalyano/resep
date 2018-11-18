<?php

namespace App\Http\Controllers;

use App\Resep;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resep(Request $r)
    {
        return Resep::all();
    }

    public function cari_resep(Request $r)
    {
        $resep= new Resep::where('judul', 'LIKE', '%'.$r->query.'%')->get();
        return $resep;
    }

    public function update_resep (Request $r)
    {
        //validasi input
        if(empty($r->judul) && empty($r->foto) && empty($r->bahan) && empty($r->langkah)) {
            return 'judul, foto, bahan, dan langkah wajib diisi';
        }
        $resep= Resep::findOrFail($r->id_resep);
        $resep->judul=$r->judul;
        $resep->foto=$r->foto;
        $resep->bahan=$r->bahan;
        $resep->langkah=$r->langkah;
        $resep->waktu_post=$r->waktu_post;
        $resep->id_kategori=$r->id_kategori;
        $resep->username=$r->username;
        $resep->link_video=$r->link_video;
        $resep->save();
    }

    public function delete_resep(Request $r)
    {
        $resep= Resep::findOrFail($r->id_resep);
        $resep->delete();
        return 'sukses';
    }

    public function buat_resep(Request $r)
    {
        //validasi input
        if(empty($r->judul) && empty($r->foto) && empty($r->bahan) && empty($r->langkah)) {
            return 'judul, foto, bahan, dan langkah wajib diisi';
        }

        $resep= new Resep;
        $resep->judul=$r->judul;
        $resep->foto=$r->foto;
        $resep->bahan=$r->bahan;
        $resep->langkah=$r->langkah;
        $resep->waktu_post=$r->waktu_post;
        $resep->id_kategori=$r->id_kategori;
        $resep->username=$r->username;
        $resep->link_video=$r->link_video;
        $resep->save();
        return 'sukses';
    }

    public function detail_resep(Request $r)
    {
        $resep= Resep::findOrFail($r->id_resep);
        return $resep;
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
     * @param  \App\Resep  $resep
     * @return \Illuminate\Http\Response
     */
    public function show(Resep $resep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resep  $resep
     * @return \Illuminate\Http\Response
     */
    public function edit(Resep $resep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resep  $resep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resep $resep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resep  $resep
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resep $resep)
    {
        //
    }
}
