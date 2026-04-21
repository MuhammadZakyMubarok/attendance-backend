<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaving extends Model
{
    protected $table = 'hrd.leaving';
    public $timestamps = false;
    protected $fillable = [
        'leave',
        'dt_leave',
        'dt_mulai',
        'dt_selesai',
        'unique_id',
        'long_period',
        'sisa',
        'user_id'
    ];
}
