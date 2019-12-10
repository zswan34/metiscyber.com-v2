<?php

namespace App\Http\Controllers;

use App\LeadStatus;
use App\Libs\Avatar;
use App\LifeCycle;
use App\Mail\NewCustomerMail;
use App\User;
use App\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        return view('pages.customers.customers');
    }

    public function getCustomer($customer_uid)
    {
        $user = User::findByUid($customer_uid);
        return view('pages.customers.customer', ['user' => $user]);
    }

    public function postCustomersCreate()
    {
        // TODO: Add validation
        $name = request('customer-name');
        $email = request('customer-email');
        $phone = request('customer-phone');
        $status = request('customer-lead-status');
        $cycle = request('customer-life-cycle');
        $assignedTo = request('customer-assigned');
        $lifeCycle = LifeCycle::where('value', $cycle)->first();
        $leadStatus = LeadStatus::where('value', $status)->first();
        $assigned = User::findByUid($assignedTo);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = null;
        $user->phone = $phone;
        $user->customer = true;
        $user->life_cycle_id = $lifeCycle->id;
        $user->lead_status_id = $leadStatus->id;
        $user->assigned_to_id = $assigned->id;
        $user->created_by_id = auth()->user()->id;

        if ($user->save()) {

            if (!request('customer-skip-email-verification')) {
                Mail::to($user->email)
                    ->queue(new NewCustomerMail($user));
            }

            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false
            ];
        }

        //return request()->all();
    }

    public function getCustomersApi()
    {
        $customers = User::where('customer', true)->get();
        foreach($customers as $customer) {
            $customer->timezone = $customer->getTimezone();
            $customer->avatar_url = Avatar::render($customer->email);
            $customer->assigned_to = User::where('id', $customer->assigned_to_id)
                ->first(['uid', 'name', 'email']);
            $customer->url = route('get-customer', ['customer_uid' => $customer->uid]);
            unset($customer['timezone_id']);
        }
        return datatables($customers)->toJson();
    }

    public function getCustomerApi($customer_uid)
    {
        $users = DB::table('users')
            ->leftJoin('user_types as ut', 'users.user_type_id', '=', 'ut.id')
            ->leftJoin('life_cycles as lc', 'users.life_cycle_id', '=', 'lc.id')
            ->leftJoin('lead_statuses as ls', 'users.lead_status_id', '=', 'ls.id')
            ->where('users.uid', $customer_uid)
            ->where('users.customer', true)
            ->select('*', 'ut.name as user_type_name', 'users.name as name',
                'users.id as id', 'lc.value as life_cycle_value', 'ls.value as lead_status_value')
            ->get();

        foreach($users as $user) {
            $ldap = '';
            $tmpUser = User::where('id', $user->id)->first();
            $roles = $tmpUser->getRoleNames();
            $user->avatar_url = Avatar::render($user->email);
            $user->assigned_to = User::where('id', $user->assigned_to_id)
                ->first(['uid', 'name', 'email']);
            if ($user->ldap_user) {
                $ldapUser = $this->ldap->search()->where('mail', $user->email)->first();
                $ldap = [
                    'cn' => $ldapUser['cn'][0],
                    'mail' => $ldapUser['mail'][0],
                    'givenname' => $ldapUser['givenname'][0],
                    'sn' => $ldapUser['sn'][0],
                    'uid' => $ldapUser['uid'][0],
                    'dn' => $ldapUser['distinguishedname'][0]
                ];
            }
            $user->meta = [
                'ldap' => $ldap,
                'roles' => $roles,
                'sessions' => UserSession::where('user_id', $user->id)->first(),
            ];
            $user->timezone = $tmpUser->getTimezone();
        }
        return datatables($users)->toJson();
    }
}
