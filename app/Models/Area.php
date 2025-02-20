<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'axis_id'
    ];

    public function team()
    {
        return $this->hasMany(AreaTeam::class, 'area_id');
    }

    public function leaderTeam()
    {
        return $this->hasMany(AreaTeam::class)->where('type', 1);
    }

    public function memberTeam()
    {
        return $this->hasMany(AreaTeam::class)->where('type', 0);
    }

    public function location()
    {
        return $this->hasOne(AreaLocation::class, 'area_id');

    }

    public function axis()
    {
        return $this->belongsTo(Axis::class,'axis_id');
    }


}
