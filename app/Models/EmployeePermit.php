<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePermit extends Model
{
    protected $table = 'hrd.employee_permit';
    public $timestamps = false;
    protected $fillable = [
        'dt_permit',
        'needs',
        'purpose',
        'dt_mulai',
        'dt_selesai',
        'jam_mulai',
        'jam_selesai',
        'long_period',
        'permit_statement',
        'user_id',
        'unique_id',
    ];
}
