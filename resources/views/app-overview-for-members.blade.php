<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/home.css'])
</head>
<body class="bg-gray-100 font-sans bg-image">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-2" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

    <div class="container h-fit  p-4 absolute inset-0 m-auto rounded-lg bg-violet-500 bg-opacity-30">
        <h1 class="text-4xl font-bold text-center mb-8 mt-4">Welcome to quizzes application!</h1>
        <p class="text-lg text-center mb-8">Manage your quizzes and stay updated with important information using our Quiz Management System. Register for an account to get started!</p>
        <div class="max-w-4xl mx-auto mb-4">
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Effortless Quiz Taking</h2>
                    <p>Take quizzes at your convenience, whether it's an in-time quiz or an out-of-time quiz. Access quizzes anytime, anywhere.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Receive Important Updates</h2>
                    <p>Get notified about upcoming quizzes and important announcements related to your quizzes via email.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Access Detailed Reports</h2>
                    <p>View detailed reports of your quiz attempts, including your scores and performance statistics.</p>
                </li>
            </ul>
        </div>
        <div class="text-end mt-8">
            <a href="{{ route('filament.member.auth.register') }}" class="bg-gray-500 rounded-xl hover:bg-gray-700 text-white font-bold py-2 px-8">Register for an Account</a>
        </div>
    </div>
</body>
</html>
