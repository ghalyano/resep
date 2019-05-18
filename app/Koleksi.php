<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koleksi extends Model
{
    protected $table = "koleksi";
    protected $primaryKey = "id_koleksi";
    public $timestamps = false;

    public function resep()
    {
        return $this->hasOne('App\Resep', 'id_resep', 'id_resep');
    }

    public function list_koleksi()
    {
        return $this->belongsTo('App\ListKoleksi', 'id_list', 'id_list');
    }
}
