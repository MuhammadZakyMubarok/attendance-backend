<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'hrd.absensi';

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
