<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Teacher Dashboard - {{ config('app.name', 'SmartSchoolPro') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen">
        <aside class="w-64 bg-white border-r p-4">
            <div class="mb-6">
                <h2 class="text-lg font-bold">Teacher Panel</h2>
                <div class="text-sm text-gray-500">{{ Auth::user()->name }}</div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('teacher.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Dashboard</a>
                <a href="{{ route('teacher.attendance.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Attendance</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100">My Classes</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100">Profile</a>
            </nav>
            <div class="mt-6 border-t pt-4">
                <form id="teacher-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-red-600 hover:text-red-800">Logout</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-auto">
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
