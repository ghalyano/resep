<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table="tbl_kategori";
    protected $primaryKey = "id_kategori"
    public $timestamps = false;
    function resep (){
    	return $this->hasMany("App\Resep", 'id_kategori', 'id_kategori');
    }
}
