<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        return view('pages.customers.customers');
    }

    public function postCustomersCreate()
    {
        return request()->all();
    }

    public function getCustomersApi()
    {
        $customers = User::where('client', true)->get();
        foreach($customers as $customer) {
            $customer->timezone = $customer->getTimezone();
            unset($customer['timezone_id']);
        }
        return datatables($customers)->toJson();
    }
}
