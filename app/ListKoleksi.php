<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListKoleksi extends Model
{
    protected $table="list_koleksi";
    protected $primaryKey = "id_list";
    public $timestamps = false;

    function resep() {
    	return $this->belongsToMany('App\Resep', 'koleksi', 'id_resep', 'id_resep');
    }
}
