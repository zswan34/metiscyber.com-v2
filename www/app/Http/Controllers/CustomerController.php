<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        return view('pages.customers.customers');
    }

    public function getCustomer()
    {
        return view('pages.customers.customer');
    }

    public function postCustomersCreate()
    {
        return request()->all();
    }

    public function getCustomersApi()
    {
        $customers = User::where('customer', true)->get();
        foreach($customers as $customer) {
            $customer->timezone = $customer->getTimezone();
            $customer->url = route('get-customer', ['customer_uid' => $customer->uid]);
            unset($customer['timezone_id']);
        }
        return datatables($customers)->toJson();
    }

    public function getCustomerApi($customer_uid)
    {
        $users = DB::table('users')
            ->leftJoin('user_types as ut', 'users.user_type_id', '=', 'ut.id')
            ->where('users.uid', $customer_uid)
            ->where('users.customer', true)
            ->select('*', 'ut.name as user_type_name', 'users.name as name',
                'users.id as id')
            ->get();


        return datatables($users)->toJson();
    }
}
