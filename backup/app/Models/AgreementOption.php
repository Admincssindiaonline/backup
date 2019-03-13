<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgreementOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prompt'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function agreement()
    {
        return $this->belongsTo(\App\Models\Agreement::class);
    }

}
