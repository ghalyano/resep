<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table="tbl_resep";
    protected $primaryKey = "id_resep"
    public $timestamps = false;

    function user() {
    	$this->belongsTo('App\Users', 'username', 'username');
    }

    function kategori() {
    	$this->belongsTo('App\kategori', 'id_kategori', 'id_kategori');
    }

    function like() {
    	$this->hasMany('App\Like', 'id_like', 'id_like');
    }

    function komentar() {
    	$this->hasMany('App\Komentar', 'id_resep', 'id_resep');
    }

    function koleksi() {
    	$this->belongsToMany('App\ListKoleksi', 'koleksi', 'id_list', 'id_resep');
    }
}
