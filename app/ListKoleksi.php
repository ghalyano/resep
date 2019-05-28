<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListKoleksi extends Model
{
    protected $table = "list_koleksi";
    protected $primaryKey = "id_list";
    public $timestamps = false;

    public function username()
    {
        return $this->belongsTo('App\Users', 'username', 'username');
    }

    public function koleksi()
    {
        return $this->hasMany('App\Koleksi', 'id_list', 'id_list');
    }

    public function resep()
    {
        return Koleksi::where('id_list', $this->id_list)->get();
    }
}
