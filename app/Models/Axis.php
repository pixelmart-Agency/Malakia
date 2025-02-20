<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Axis extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function dailyReport()
    {
        return $this->hasMany(DailyReport::class, 'axis_id', 'id');
    }

    public function axisQuestions()
    {

        return $this->hasMany(AxisQuestion::class);

    }


}
