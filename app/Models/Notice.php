<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'notice_type_id',
        'lat',
        'description',
        'long',
        'notice_time',
        'notice_date',
        'user_id',
        'status',
        'refuse_reason',
        'refuse_notice',
        'admin_id',
        'leader_id',
    ];


    public function noticeType()
    {
        return $this->belongsTo(NoticeType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function media()
    {
        return $this->morphMany('App\Models\Media', 'model');

    }
}
