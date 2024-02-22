<x-mail::message>
# Your exam result has been calculated
## {{$quiz->title}}
## {{$quiz->description}}
<x-mail::button :url="$quizUrl" color="success">
Show Exam Result
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
{{ $name }}
</x-mail::message>




