<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait UserActionTracking
{
    use SoftDeletes;

    public static function bootUserActionTracking()
    {
        static::creating(function ($model) {
            if (! $model->isDirty('created_by')) {
                $model->created_by = is_null(auth()->user()) ? null : auth()->user()->email;
            }
        });

        static::updating(function ($model) {
            if (! $model->isDirty('updated_by')) {
                $model->updated_by = is_null(auth()->user()) ? null : auth()->user()->email;
            }
        });

        static::deleting(function ($model) {
            if (! $model->isDirty('deleted_by')) {
                $model->deleted_by = is_null(auth()->user()) ? null : auth()->user()->email;
                $model->save();
            }
        });
    }
}
