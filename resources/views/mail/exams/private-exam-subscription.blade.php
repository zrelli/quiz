<x-mail::message>
# You have subscribed to new quiz exam
## {{$quiz->title}}
## {{$quiz->description}}
<x-mail::button :url="$urlToken . '/accept'" color="success">
    Accept invitation
</x-mail::button>
<x-mail::button :url="$urlToken . '/decline'" color="error">
    Decline invitation
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>
