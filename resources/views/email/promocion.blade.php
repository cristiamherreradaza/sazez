@component('mail::message')
# Introduction

The body of your message.
{{ $promocion->nombre }}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
