@component('mail::message')
Payment received

{{$request}}

@component('mail::button', ['url' => ''])
No link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
