<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class adminLogin extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'email', 'password', 'fullname', 'phonenumber'
    ];
    protected $primaryKey = 'admin_id';
    protected $table = 'tbl_admin';
}
