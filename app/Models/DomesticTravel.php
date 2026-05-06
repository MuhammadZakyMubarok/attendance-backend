<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomesticTravel extends Model
{
    protected $table = 'hrd.travel';
    protected $fillable = [
        'dt_leave',
        'dt_arrive',
    ];
}
