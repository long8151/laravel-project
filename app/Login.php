<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'client_email', 'password', 'client_fullname', 'client_phonenumber'
    ];
    protected $primaryKey = 'client_id';
    protected $table = 'tbl_client';
}
