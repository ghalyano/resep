<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koleksi extends Model
{
    protected $table="koleksi";
    protected $primaryKey = "id_koleksi";
    public $timestamps = false;

    public function resep()
    {
        return $this->hasOne('App\Resep', 'id_resep', 'id_resep');
    }

    function user() {
    	return $this->belongsTo('App\Users', 'username', 'username');
    }
}
