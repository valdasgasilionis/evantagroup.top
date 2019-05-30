@component('mail::message')
Confirm new user (email)
{{$user->email}}

@component('mail::button', ['url' => url('/confirm_new/'.$user->id)])
confirm here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
