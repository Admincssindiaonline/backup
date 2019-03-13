<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name', 'subject', 'initial_text'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['accepted_at', 'created_at', 'updated_at', 'deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = str_random(16);
            return true;
        });

        static::created(function ($model) {
            broadcast(new \App\Events\AgreementEvent($model, 'created'));
            return true;
        });

        static::updated(function ($model) {
            broadcast(new \App\Events\AgreementEvent($model, 'updated'));
            return true;
        });

        static::deleted(function ($model) {
            broadcast(new \App\Events\AgreementEvent($model, 'deleted'));
            return true;
        });
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function options()
    {
        return $this->hasMany(\App\Models\AgreementOption::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token';
    }
}
