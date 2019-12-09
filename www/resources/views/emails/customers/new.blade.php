@component('mail::message')
# Welcome, {{ explode(' ', $user->name)[0] }}

Thank you for choosing Metis Cyber to help your business.

We first need to verify your email address by clicking the link below.

@component('mail::button', ['url' => ''])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
