@component('mail::message')
Confirm new user (email)
{{$request}}

@component('mail::button', ['url' => url('/confirm_new/'.$request)])
confirm here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
