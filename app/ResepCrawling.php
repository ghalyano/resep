<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResepCrawling extends Model
{
    protected $table="tbl_resep_crawling";
    protected $primaryKey = "id_resepcrawling";
    public $timestamps = false;
}
