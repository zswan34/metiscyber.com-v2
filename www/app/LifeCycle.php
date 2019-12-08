<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LifeCycle extends Model
{
    protected $fillable = [
        'name', 'value'
    ];

    public static function exceptByName($name) {
        if ($lifecycle = LifeCycle::where('name', '!=', $name)->get())
        {
            return $lifecycle;
        }
    }
}
