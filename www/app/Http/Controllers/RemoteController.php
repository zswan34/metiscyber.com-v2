<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RemoteController extends Controller
{
    public function checkRemoteValue($model, $value) {
        switch ($model) {
            case 'users':
                if ($value === 'email') {
                    return (!User::where('email', request('customer-email'))->exists()) ? 'true' : 'false';
                }
                break;
        }
    }
}
