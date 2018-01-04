<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'url',
        'latitude',
        'longitude',
    ];
}
