<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'client_id', 'checkClientInfo_id', 'payment_id', 'total', 'status'
    ];
    protected $primaryKey = 'order_id';
    protected $table = 'tbl_order';
}
