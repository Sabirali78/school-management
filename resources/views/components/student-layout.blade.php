<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Portal - {{ config('app.name', 'SmartSchoolPro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .student-sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
        }
        .student-sidebar.collapsed {
            transform: translateX(-100%);
            width: 0;
        }
        @media (min-width: 768px) {
            .student-sidebar {
                transform: translateX(0) !important;
                width: 16rem;
            }
            .student-sidebar.collapsed {
                width: 5rem;
            }
            .student-sidebar.collapsed .sidebar-text {
                display: none;
            }
            .student-sidebar.collapsed .logo-text {
                display: none;
            }
            .student-sidebar.collapsed .nav-item {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            .student-sidebar.collapsed + .student-content {
                margin-left: 5rem;
            }
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .nav-item.active {
            background-color: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3b82f6;
            color: #1d4ed8;
        }
        .mobile-menu-button {
            display: block;
        }
        @media (min-width: 768px) {
            .mobile-menu-button {
                display: none;
            }
        }
        .student-content {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .notification-dot {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #ef4444;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: false,
    notificationsOpen: false,
    profileOpen: false 
}">
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <div class="flex min-h-screen">
        <!-- Student Sidebar -->
        <aside :class="{ 'collapsed': sidebarCollapsed, 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" 
               class="student-sidebar fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-50 to-white border-r border-blue-100 md:static md:inset-auto md:translate-x-0 transform transition-all duration-300 ease-in-out overflow-y-auto">
            
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                            </svg>
                        </div>
                        <div class="logo-text">
                            <h1 class="text-lg font-bold text-gray-900">Student<span class="text-blue-600">Portal</span></h1>
                            <p class="text-xs text-gray-500">SmartSchoolPro</p>
                        </div>
                    </div>
                    
                    <!-- Desktop Toggle Button -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed" 
                            class="hidden md:block p-1.5 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600">
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Student Profile Summary -->
            <div class="p-4 border-b border-blue-100" :class="{ 'hidden': sidebarCollapsed }">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-600">
                            @if(Auth::user()->student)
                                {{ Auth::user()->student->class->name ?? 'N/A' }} • {{ Auth::user()->student->section->name ?? 'N/A' }}
                            @else
                                Student
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">Roll No: {{ Auth::user()->student->roll_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('student.dashboard') }}" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('student.dashboard') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <!-- Timetable -->
                <a href="{{ route('student.timetable') }}" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('student.timetable') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="sidebar-text">Timetable</span>
                </a>

                <!-- Attendance -->
                <a href="{{ route('student.attendance') }}" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('student.attendance') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="sidebar-text">Attendance</span>
                    <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-full">3</span>
                </a>

                <!-- Assignments -->
                <a href="{{ route('student.assignments') }}" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('student.assignments') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sidebar-text">Assignments</span>
                    <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">2</span>
                </a>

                <!-- Exams & Results -->
                <div x-data="{ open: {{ request()->is('student/exams*') || request()->is('student/results*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="nav-item w-full flex items-center justify-between px-3 py-3 text-sm font-medium rounded-lg {{ request()->is('student/exams*') || request()->is('student/results*') ? 'active text-blue-700' : 'text-gray-700' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="sidebar-text">Exams & Results</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                        <a href="#" 
                           class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded">
                            Exam Schedule
                        </a>
                        <a href="#" 
                           class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded">
                            My Results
                        </a>
                        <a href="#" 
                           class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded">
                            Report Card
                        </a>
                    </div>
                </div>

                <!-- Fees -->
                <a href="#" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('#') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-text">Fee Details</span>
                </a>

     

        

                <!-- Settings -->
                <a href="#" 
                   class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('#') ? 'active text-blue-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="sidebar-text">Settings</span>
                </a>
            </nav>

            <!-- Quick Stats (Visible when not collapsed) -->
            <div class="p-4 border-t border-blue-100" :class="{ 'hidden': sidebarCollapsed }">
                <div class="space-y-3">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xs text-blue-800 font-medium">Attendance</p>
                        <p class="text-lg font-bold text-blue-900">94%</p>
                        <div class="w-full bg-blue-200 rounded-full h-1.5 mt-1">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 94%"></div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xs text-green-800 font-medium">Avg. Score</p>
                        <p class="text-lg font-bold text-green-900">85%</p>
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="p-4 border-t border-blue-100" :class="{ 'hidden': sidebarCollapsed }">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="student-content flex-1 flex flex-col min-h-screen md:ml-0">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left: Mobile menu button and breadcrumb -->
                        <div class="flex items-center">
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="mobile-menu-button p-2 rounded-lg text-gray-500 hover:bg-gray-100 md:hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            
                            <!-- Page Title -->
                            <div class="ml-4">
                                <h1 class="text-xl font-bold text-gray-900">
                                    @yield('page-title', 'Student Dashboard')
                                </h1>
                                <p class="text-sm text-gray-600">
                                    @yield('page-subtitle', 'Welcome to your learning portal')
                                </p>
                            </div>
                        </div>

                        <!-- Right: Student Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Search (Desktop only) -->
                            <div class="hidden md:block relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="search" 
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64" 
                                       placeholder="Search...">
                            </div>

                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <h3 class="font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <!-- Notification Items -->
                                        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-gray-900">New assignment posted</p>
                                                <p class="text-xs text-gray-500">Mathematics - Due tomorrow</p>
                                            </div>
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-gray-900">Result published</p>
                                                <p class="text-xs text-gray-500">Science exam - Scored 85%</p>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-blue-50 border-t border-gray-100">
                                        View All Notifications
                                    </a>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="hidden md:block relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center space-x-2 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span>Quick Access</span>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="{{ route('student.assignments') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Submit Assignment
                                    </a>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        View Fee Status
                                    </a>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Message Teacher
                                    </a>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        View Calendar
                                    </a>
                                </div>
                            </div>

                            <!-- Student Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">Student</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        My Profile
                                    </a>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Page Header -->
                @hasSection('page-header')
                    <div class="mb-6">
                        @yield('page-header')
                    </div>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-green-800">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-red-800">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-600">
                            © {{ date('Y') }} SmartSchoolPro Student Portal. 
                            <span class="text-blue-600 font-medium">{{ Auth::user()->student->roll_number ?? 'N/A' }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(Auth::user()->student)
                                {{ Auth::user()->student->class->name ?? '' }} • 
                                {{ Auth::user()->student->section->name ?? '' }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Session: 2024-25</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            Online
                        </span>
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="hidden md:inline-flex items-center px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                            </svg>
                            <span x-text="sidebarCollapsed ? 'Expand' : 'Collapse'"></span>
                        </button>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize Alpine.js data
            Alpine.data('studentLayout', () => ({
                sidebarOpen: false,
                sidebarCollapsed: false,
                notificationsOpen: false,
                profileOpen: false,
                
                toggleSidebar() {
                    if (window.innerWidth < 768) {
                        this.sidebarOpen = !this.sidebarOpen;
                    } else {
                        this.sidebarCollapsed = !this.sidebarCollapsed;
                    }
                },
                
                closeSidebarOnMobile() {
                    if (window.innerWidth < 768) {
                        this.sidebarOpen = false;
                    }
                }
            }));
            
            // Auto-close sidebar on mobile when clicking outside
            document.addEventListener('click', (e) => {
                const sidebar = document.querySelector('.student-sidebar');
                const mobileButton = document.querySelector('.mobile-menu-button');
                
                if (window.innerWidth < 768 && 
                    !sidebar.contains(e.target) && 
                    !mobileButton.contains(e.target)) {
                    Alpine.store('studentLayout').sidebarOpen = false;
                }
            });
            
            // Close sidebar when resizing to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    Alpine.store('studentLayout').sidebarOpen = false;
                }
            });
        });
    </script>
</body>
</html>