<x-mail::message>
# You have been invited to subscribe to a new quiz exam
## {{$quiz->title}}
## {{$quiz->description}}
<x-mail::button :url="$urlToken" color="success">
    Show invitation
</x-mail::button>
{{-- <x-mail::button :url="$urlToken . '/decline'" color="error">
    Decline invitation
</x-mail::button> --}}
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>
