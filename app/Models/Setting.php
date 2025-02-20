<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'checkin_date',
        'checkout_date',
        'checkin_max_date',
        'checkout_max_date',
        'north',
        'south',
        'east',
        'west',
        'location_url',
    ];


}
