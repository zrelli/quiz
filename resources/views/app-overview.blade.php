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

    <div class="container h-fit  p-4 absolute inset-0 m-auto rounded-lg bg-orange-500 bg-opacity-30">
        <h1 class="text-4xl font-bold text-center mb-8 mt-4">Welcome to Our Quiz Management System!</h1>
        <p class="text-lg text-center mb-8">Are you looking for a comprehensive solution to manage quizzes efficiently?
            Look no further! Our Quiz Management System is designed to meet all your needs, whether you're an
            educational
            institution, a corporate training program, or an online learning platform.</p>
        <div class="max-w-4xl mx-auto mb-4">
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Effortless Tenant Management</h2>
                    <p>Easily register and manage multiple tenants (clients) with a single codebase. Each tenant has its
                        own secure space to manage quizzes and users.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Flexible Quiz Creation</h2>
                    <p>Create quizzes with ease, including in-time quizzes (with start and end times) and out-of-time
                        quizzes (accessible anytime). Customize quizzes to suit your needs.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">User-Friendly Interface</h2>
                    <p>Our system provides an intuitive interface for managing questions, choices, and user accounts.
                        Easily add, edit, and organize quiz content.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Comprehensive Reporting</h2>
                    <p>View detailed dashboards with key statistics, including the number of members, attempts, pass
                        rates, fail rates, average scores, and more.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Efficient Communication</h2>
                    <p>Automatically email members with quiz results and reminders. Notify tenant owners about quiz
                        completions.</p>
                </li>
                <li class="bg-white rounded-lg shadow-lg p-6 transition-transform transform hover:scale-105">
                    <h2 class="text-xl font-bold mb-4">Integration with Google Calendar</h2>
                    <p>Integrate quizzes with Google Calendar to schedule quiz events, ensuring members never miss an
                        important quiz.</p>
                </li>
            </ul>
        </div>
        <div class="text-end mt-8">
            <a href="{{ route('request-company-account') }}" class="bg-gray-500 rounded-xl hover:bg-gray-700 text-white font-bold py-2 px-8">Register
                here</a>
        </div>
    </div>
</body>
</html>
