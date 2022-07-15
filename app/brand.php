<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'brand_name', 'status'
    ];
    protected $primaryKey = 'brand_id';
    protected $table = 'tbl_brand';
}
