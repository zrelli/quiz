<x-mail::message>
# Your Company Account Request Mail
## Your request has been accepted and you could login to your admin panel.
<x-mail::button :url="$quizUrl" color="success">
Login to Admin Panel
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>




