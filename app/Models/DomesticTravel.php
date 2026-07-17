<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomesticTravel extends Model
{
    protected $table = 'hrd.travel';
    protected $fillable = [
        'approved_by',
        'remark',
        'applicant_id',
        'start_date',
        'end_date',
        'date_approved',
        'ins_date',
        'pm_id',
    ];
}
