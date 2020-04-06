<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCenterTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'address',
        'slug'
    ];
}
