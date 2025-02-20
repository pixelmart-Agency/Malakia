<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'national_id',
        'image',
        'phone',
        'email',
        'fcm_token',
        'jwt_token',
        'password',
        'otp',
        'otp_expire',
        'status',
        'delete_reason'
    ];

    public function areas()
    {
        return $this->hasMany(AreaTeam::class);
    }

    public function leaderReports()
    {
        return $this->hasMany(DailyReportAssignUser::class, 'leader_id', 'id');
    }

    public function userDailyReports()
    {
        return $this->hasMany(DailyReportAssignUser::class, 'user_id', 'id');
    }

    public function userNotices()
    {
        return $this->hasMany(Notice::class, 'user_id', 'id');
    }

    public function alerts()
    {
        return $this->hasMany(AlertUser::class,'user_id','id');
    }

   public function leaderalerts()
    {
        return $this->hasMany(AlertLeader::class,'leader_id','id');
    }

    public function leaderNotices()
    {
        return $this->hasMany(Notice::class, 'leader_id', 'id');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    public function attendancesDay()
    {
        return $this->HasOne(Attendance::class, 'user_id', 'id')
            ->whereDate('date', Carbon::now()->format('Y-m-d'));
    }

    public function attendancesLastTwoDays()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id')
            ->select('date', 'checkin', 'checkout')
            ->whereDate('date', '>=', Carbon::now()->subDays(2)->format('Y-m-d'));
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }


    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($model)
                ->log('created');
        });

        static::updated(function ($model) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($model)
                ->log('updated');
        });

        static::deleted(function ($model) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($model)
                ->log('deleted');
        });
    }

}
