@component('mail::message')
Confirm new user (email)
{{$request}}

@component('mail::button', ['url' => url('/confirm_new/'.$user->id)])
confirm here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
