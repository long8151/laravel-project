<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'category_id', 'brand_id', 'product_name', 'price', 'quantity', 'image', 'description','status'
    ];
    protected $primaryKey = 'product_id';
    protected $table = 'tbl_product';
}
