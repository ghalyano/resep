<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table="like";
    protected $primaryKey = "id_like";
    public $timestamps = false;

    function resep (){
    	return $this->belongsTo("App\Resep", 'id_resep', 'id_resep');
    }
     function users (){
    	return $this->belongsTo("App\Users", 'username', 'username');
    }
}
