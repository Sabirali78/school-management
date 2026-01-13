<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartSchoolPro - Complete School Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="font-sans antialiased">

    <!-- Header Component -->
    <livewire:header />

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Transform Your School with <br>
                    <span class="text-yellow-300">Smart Management</span>
                </h1>
                <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90">
                    A comprehensive school management system that streamlines administration, 
                    enhances learning, and connects teachers, students, and parents seamlessly.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-100 transition">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-100 transition">
                            Get Started Free
                        </a>
                        <a href="{{ route('login') }}" 
                           class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-white hover:text-indigo-600 transition">
                            Live Demo
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-8 rounded-xl shadow hover:translate-y-[-5px] transition-transform">
                    <div class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-indigo-500 to-purple-600">500+</div>
                    <p class="text-gray-600 font-semibold mt-2">Schools Using</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow hover:translate-y-[-5px] transition-transform">
                    <div class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-indigo-500 to-purple-600">50K+</div>
                    <p class="text-gray-600 font-semibold mt-2">Students Managed</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow hover:translate-y-[-5px] transition-transform">
                    <div class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-indigo-500 to-purple-600">95%</div>
                    <p class="text-gray-600 font-semibold mt-2">Parent Satisfaction</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow hover:translate-y-[-5px] transition-transform">
                    <div class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-indigo-500 to-purple-600">24/7</div>
                    <p class="text-gray-600 font-semibold mt-2">Support Available</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">Everything You Need in One Platform</h2>
                <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto">
                    Our comprehensive solution covers all aspects of school management from admission to graduation.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Repeatable Feature Card -->
                    <div class="bg-white p-8 rounded-xl shadow hover:translate-y-[-5px] transition-transform border border-gray-100">
                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Student Management</h3>
                        <p class="text-gray-600">Complete student profiles, admission tracking, academic records, and progress monitoring.</p>
                    </div>
                    <!-- Add remaining 5 feature cards using same structure and Tailwind classes -->
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-indigo-50 py-20 text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Transform Your School?</h2>
                <p class="text-gray-600 mb-10 text-lg">Join hundreds of schools that have modernized their management with SmartSchoolPro.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-indigo-700 transition">
                        Start Free Trial
                    </a>
                    <a href="#" 
                       class="bg-white text-indigo-600 border border-indigo-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-indigo-50 transition">
                        Schedule a Demo
                    </a>
                </div>
                <p class="text-gray-500 mt-6">No credit card required • 30-day free trial • Full support</p>
            </div>
        </section>
    </main>

    <!-- Footer Component -->
    <x-footer />
</body>
</html>
