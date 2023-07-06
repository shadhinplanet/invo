@component('mail::message')
# Hello,

A new user '{{ $user->name }}' just get registered to Invo website.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
