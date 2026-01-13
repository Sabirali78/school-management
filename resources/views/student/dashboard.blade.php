<x-student-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">My Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold mb-4">Profile</h2>
            <div class="text-sm"><strong>Name:</strong> {{ $student->user->name }}</div>
            <div class="text-sm"><strong>Email:</strong> {{ $student->user->email }}</div>
            <div class="text-sm"><strong>Class:</strong> {{ $student->class->name ?? 'N/A' }}</div>
            <div class="text-sm"><strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</div>
            <div class="text-sm"><strong>Roll:</strong> {{ $student->roll_number }}</div>

            <hr class="my-4" />

            <h3 class="font-semibold mb-2">Recent Attendance</h3>
            @if($attendanceDetails->count())
                <div class="space-y-2">
                    @foreach($attendanceDetails as $ad)
                        <div class="flex items-center justify-between border-b py-2">
                            <div class="text-sm">{{ $ad->date }}</div>
                            <div class="text-sm">{{ ucfirst($ad->status) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500">No attendance records found.</div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold mb-4">Attendance Summary</h2>
            <div class="text-sm"><strong>Total Records:</strong> {{ $total }}</div>
            <div class="text-sm"><strong>Present:</strong> {{ $present }}</div>
            <div class="text-sm"><strong>Percent:</strong> {{ $attendancePercent ?? 'N/A' }}%</div>
            <div class="mt-4">
                <a href="{{ route('student.attendance') }}" class="btn-primary">View Full Attendance</a>
            </div>
        </div>
    </div>
</x-student-layout>

<x-student-layout>
    @section('page-title', 'Student Dashboard')
    @section('page-subtitle', 'Welcome back, ' . Auth::user()->name . '!')
    
    @section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}! Here's your academic summary.</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Week {{ now()->weekOfYear }}
            </span>
        </div>
    </div>
    @endsection

    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Attendance Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Attendance</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">94%</p>
                        <p class="text-sm text-green-600 mt-1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            +2% from last month
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Present: 47</span>
                        <span>Total: 50</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 94%"></div>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Average Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">85%</p>
                        <p class="text-sm text-blue-600 mt-1">A Grade</p>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Last Exam: 88%</span>
                        <span>Trend: ↗</span>
                    </div>
                </div>
            </div>

            <!-- Pending Assignments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Pending Assignments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                        <p class="text-sm text-yellow-600 mt-1">2 due this week</p>
                    </div>
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('student.assignments') }}" 
                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                        View assignments
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Next Class -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Next Class</p>
                        <p class="text-xl font-bold text-gray-900 mt-2">Mathematics</p>
                        <p class="text-sm text-gray-600 mt-1">10:00 AM - Room 205</p>
                    </div>
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        In 45 minutes
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Timetable & Recent Notices -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Today's Timetable -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Today's Schedule</h3>
                        <p class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <a href="{{ route('student.timetable') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View Full Timetable
                    </a>
                </div>
                
                <div class="space-y-4">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                        <div class="flex-shrink-0 w-16 text-center">
                            <div class="text-lg font-bold text-blue-600">{{ 8 + $i * 2 }}:00</div>
                            <div class="text-sm text-gray-500">{{ 9 + $i * 2 }}:00</div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-medium text-gray-900">Subject {{ $i }}</h4>
                            <p class="text-sm text-gray-600">Room {{ 200 + $i }} • Mr. Teacher {{ $i }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ongoing
                            </span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Notices</h3>
                    <a href="{{ route('student.notices') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View All
                    </a>
                </div>
                
                <div class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Important Announcement {{ $i }}</h4>
                                <p class="text-xs text-gray-600 mt-1">School will remain closed on Friday for maintenance.</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-xs text-gray-500">{{ $i }} hour{{ $i > 1 ? 's' : '' }} ago</span>
                                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Important
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Upcoming Exams & Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upcoming Exams -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Exams</h3>
                        <p class="text-sm text-gray-600">Prepare for these upcoming assessments</p>
                    </div>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View Schedule
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @for($i = 1; $i <= 3; $i++)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">Mathematics {{ $i }}</div>
                                    <div class="text-sm text-gray-500">Mid Term</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-gray-900">{{ now()->addDays($i)->format('M d, Y') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900">09:00 AM</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900">Hall {{ 100 + $i }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($i == 1)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                        Tomorrow
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Upcoming
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Links</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('student.assignments') }}" 
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Submit Assignment</h4>
                            <p class="text-sm text-gray-600">Math assignment due today</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('student.fees') }}" 
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-sm transition">
                        <div class="p-2 bg-green-100 text-green-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Fee Status</h4>
                            <p class="text-sm text-gray-600">Check payment due dates</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('student.library') }}" 
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-sm transition">
                        <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Library</h4>
                            <p class="text-sm text-gray-600">Borrow or return books</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('student.messages') }}" 
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-pink-300 hover:shadow-sm transition">
                        <div class="p-2 bg-pink-100 text-pink-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Messages</h4>
                            <p class="text-sm text-gray-600">2 unread messages</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>