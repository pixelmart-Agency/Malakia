<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function roomMessages()
    {
        return $this->hasMany(RoomMessage::class);
    }


}
