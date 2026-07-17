<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelDetail extends Model
{
    protected $table = 'hrd.travel_detail';
    protected $fillable = [
        'project_number',
        'project_name',
        'description',
        'category',
    ];
}
