@component('mail::message')
Payment received

Rental id number: {{$rental->id}}
Rental start date: {{$rental->start}}
Rental end date: {{$rental->end}}
Price paid: {{$rental->price}}

@component('mail::button', ['url' => url('https::/evantagroup.top')])
VANAGUPE
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
