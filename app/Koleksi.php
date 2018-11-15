<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koleksi extends Pivot
{
    protected $table="koleksi";
    protected $primaryKey = "id_koleksi"
    public $timestamps = false;
}
