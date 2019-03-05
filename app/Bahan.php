<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table="bahan";
    protected $primaryKey = "id_bahan";
    public $timestamps = false;

    function resep()
    {
    	return $this->belongsTo("App\Resep", 'id_resep', 'id_resep');
    }
}
