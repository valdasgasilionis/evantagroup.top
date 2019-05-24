@component('mail::message')
Payment received

Rental id number: {{$request->id}}
Rental start date: {{$request->start}}
Rental end date: {{$request->end}}
Price paid: {{$request->price}}

@component('mail::button', ['url' => ''])
No link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
