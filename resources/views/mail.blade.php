@component('mail::message')
Payment received

 {{$request}}

@component('mail::button', ['url' => ''])
not active
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
