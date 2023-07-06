@component('mail::message')
# Welcome {{ $client->name }},

Here is the latest invoice.

@component('mail::panel')
The invoice is attaced.
@endcomponent

Have a nice day!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
