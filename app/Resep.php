<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = "tbl_resep";
    protected $primaryKey = "id_resep";
    protected $dates = ['waktu_post'];
    public $timestamps = false;

    function user()
    {
        return $this->belongsTo('App\Users', 'username', 'username');
    }

    function kategori()
    {
        return $this->belongsTo('App\Kategori', 'id_kategori', 'id_kategori');
    }

    function like()
    {
        return $this->hasMany('App\Like', 'id_resep', 'id_resep');
    }

    function komentar()
    {
        return $this->hasMany('App\Komentar', 'id_resep', 'id_resep');
    }

    function koleksi()
    {
        return $this->belongsToMany('App\Koleksi', 'tbl_resep', 'id_resep', 'id_resep');
    }

    function bahan()
    {
        return $this->hasMany('App\Bahan', 'id_resep', 'id_resep');
    }

    function langkah()
    {
        return $this->hasMany('App\Langkah', 'id_resep', 'id_resep');
    }

    function isLiked($username)
    {
        $resep = Like::where(['username' => $username, 'id_resep' => $this->id_resep])->first();
        if ($resep)
            return true;
        return false;
    }

    function isBookmarked($username, $id_resep)
    {
        $bookmarked = Koleksi::where('id_resep', $id_resep)->whereHas('list_koleksi', function ($q) use ($username) {
            $q->where('username', $username);
        })->first();
        if ($bookmarked)
            return true;
        return false;
    }

    function hapus_bookmark($username, $id_resep)
    {
        $bookmarked = Koleksi::where('id_resep', $id_resep)->whereHas('list_koleksi', function ($q) use ($username) {
            $q->where('username', $username);
        })->delete();
        if ($bookmarked)
            return true;
        return false;
    }
}
