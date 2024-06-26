<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test_setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'auth_token',
        'base_url',
        'test_user_email',
        'test_user_password',
    ];
}
