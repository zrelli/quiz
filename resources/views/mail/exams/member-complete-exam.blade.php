<x-mail::message>
## {{$name . ' has completed an exam'}}
## {{$quiz->title}}
## {{$quiz->description}}
<x-mail::button :url="$quizUrl" color="success">
Show Quiz
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>




