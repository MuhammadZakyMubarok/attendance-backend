<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelExpenses extends Model
{
    protected $table = 'hrd.travel_expenses';
    protected $fillable = [
        'needs',
        'days',
        'unit',
        'cost',
        'remark',
        'quantity',
        'travel_id',
    ];
}
