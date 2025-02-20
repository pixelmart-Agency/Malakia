<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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
