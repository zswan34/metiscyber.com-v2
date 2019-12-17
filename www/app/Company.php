<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'domain','phone', 'city', 'state', 'description',
        'country', 'address_id', 'employee_number', 'timezone_id',
        'company_owner_id', 'company_type_id', 'industry_id'
    ];
}
