<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = "tbl_komentar";
    protected $primaryKey = "id_komentar";
    public $timestamps = false;
    public $appends = ['foto_profil'];

    function resep()
    {
        return $this->belongsTo("App\Resep", 'id_resep', 'id_resep');
    }

    public function user()
    {
        return $this->belongsTo('App\Users', 'username', 'username');
    }

    public function getFotoProfilAttribute()
    {
        return Users::find($this->username)->foto;
    }
}
