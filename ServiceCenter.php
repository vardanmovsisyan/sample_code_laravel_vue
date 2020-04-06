<?php

//A model with translatable fields, uses soft deletes

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCenter extends Model implements TranslatableContract
{
    use Translatable;
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public $translatedAttributes = [
        'name',
        'address',
        'slug'
    ];
    protected $fillable = [
        'latitude',
        'longitude',
        'index',
        'city_id',
        'phone_number'
    ];

    public function brands()
    {
        return $this->belongsToMany('App\Brand');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }
}
