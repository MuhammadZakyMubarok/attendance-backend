<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPurposes extends Model
{
    protected $table = 'hrd.travel_purpose';
    protected $fillable = [
        'purpose',
        'travel_id',
    ];
}
