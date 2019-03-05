<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Langkah extends Model
{
    protected $table="langkah";
    protected $primaryKey = "id_langkah";
    public $timestamps = false;
    function resep()
    {
    	return $this->belongsTo("App\Resep", 'id_resep', 'id_resep');
    }
}
