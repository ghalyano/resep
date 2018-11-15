<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table="tbl_komentar";
    protected $primaryKey = "id_komentar"
    public $timestamps = false;
    
    function resep (){
    	return $this->belongsTo("App\Resep", 'id_resep', 'id_resep');
    }
}
