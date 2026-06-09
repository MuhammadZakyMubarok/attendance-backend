<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrdAttendance extends Model
{
    protected $table = 'hrd.hrdattendances';

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'location_in',
        'location_out',
        'is_approved',
        'unique_id',
    ];
}
