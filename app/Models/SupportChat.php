<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChat extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function supportChatMessages()
    {
        return $this->hasMany(SupportChatMessage::class);
    }

}
