<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table="users";
    // protected $primaryKey = "username";
    public $timestamps = false;

    function resep() 
    {
        return $this->hasMany('App\Resep', 'username', 'username');
    } 

    function koleksi() 
    {
        return $this->hasMany('App\Koleksi', 'username', 'username');
    }

    function like() 
    {
        return $this->hasMany('App\Like', 'username', 'username');
    }
}
