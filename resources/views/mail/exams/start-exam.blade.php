<x-mail::message>
## {{$name . ' is beginning an exam'}}
## {{$quiz->title}}
## {{$quiz->description}}
<x-mail::button :url="$quizUrl" color="success">
Show Quiz Details
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>




