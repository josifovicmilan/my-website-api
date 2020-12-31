@component('mail::message')
# Korisnik {{$contact->name}} je poslao poruku

{{$contact->message}}


Odgovori na<br>
{{$contact->email}}
@endcomponent
