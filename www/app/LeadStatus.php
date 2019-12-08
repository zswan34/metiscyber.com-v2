<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $fillable = [
        'name', 'value'
    ];

    public static function exceptByName($name) {
        if ($leadStatus = LeadStatus::where('name', '!=', $name)->get())
        {
            return $leadStatus;
        }
    }
}
