<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'main.employee';
    protected $fillable = [
        'email',
        'password',
        'name',
        'phone',
        'role',
        'position',
        'department',
        'image_url',
    ];

    protected $hidden = [
        'password',
        'unique_id',
    ];
}