<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600">Welcome back,! Here's what's happening today.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                <button class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Quick Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Students -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">Total Students</p>
                        <p class="stat-value">{{ number_format($totalStudents) }}</p>
                        <p class="text-sm text-green-600 mt-1">&nbsp;</p>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">Total Teachers</p>
                        <p class="stat-value">{{ number_format($totalTeachers) }}</p>
                        <p class="text-sm text-green-600 mt-1">&nbsp;</p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-100 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">Today's Attendance</p>
                        <p class="stat-value">{{ $attendancePercentage }}%</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $presentCount }} / {{ $totalStudents }} present</p>
                    </div>
                    <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Fee Collection -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">Monthly Fee Collection</p>
                        <p class="stat-value">₨ {{ number_format($collected, 2) }}</p>
                        <p class="text-sm text-green-600 mt-1">{{ $collectionPercent }}% collected</p>
                    </div>
                    <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance Overview -->
            <div class="lg:col-span-2 card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Overview</h3>
                    <p class="text-sm text-gray-600">Last 7 days attendance trend</p>
                </div>
                <div class="h-64 flex items-center justify-center border border-dashed border-gray-300 rounded-lg">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-gray-500">Attendance chart will appear here</p>
                        <p class="text-sm text-gray-400">(Integration with Chart.js or similar)</p>
                    </div>
                </div>
            </div>

            <!-- Recent Notices -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Notices</h3>
                    <a href="{{ route('admin.notices.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentNotices as $notice)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <h4 class="font-medium text-gray-900">{{ $notice->title }}</h4>
                            <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($notice->message, 120) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ $notice->created_at ? $notice->created_at->diffForHumans() : '' }}</span>
                                <span class="badge-info">Important</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No recent notices.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Students -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.attendance.today') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Take Today's Attendance</h4>
                            <p class="text-sm text-gray-600">Mark attendance for all classes</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.fee-payments.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="p-2 bg-green-100 text-green-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Collect Fee</h4>
                            <p class="text-sm text-gray-600">Record fee payment for student</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.notices.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Post New Notice</h4>
                            <p class="text-sm text-gray-600">Announcement for students/parents</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="lg:col-span-2 card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Students</h3>
                    <a href="{{ route('admin.students.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-header-cell">Student</th>
                                <th class="table-header-cell">Class</th>
                                <th class="table-header-cell">Roll No</th>
                                <th class="table-header-cell">Admission Date</th>
                                <th class="table-header-cell">Status</th>
                                <th class="table-header-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentStudents as $student)
                                @php
                                    $user = \App\Models\User::find($student->user_id);
                                    $class = \App\Models\ClassModel::find($student->class_id);
                                @endphp
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-gray-700">S{{ $student->id }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $user->name ?? ('Student ' . $student->id) }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="font-medium">{{ $class->name ?? 'Class ' . ($student->class_id ?? '') }}</span>
                                        <div class="text-sm text-gray-500">Section {{ $student->section_id ?? 'A' }}</div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="font-mono">{{ $student->roll_number ?? '' }}</span>
                                    </td>
                                    <td class="table-cell">
                                        {{-- No admission timestamp available on students table by default --}}
                                        <span class="text-sm text-gray-500">—</span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="badge-success">Active</span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                            <a href="#" class="text-green-600 hover:text-green-900">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-sm text-gray-500 p-4">No recent students.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View Calendar</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($upcomingEvents as $event)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition">
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-50 text-blue-700 rounded-lg mr-3">
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('j') }}</div>
                                <div class="text-xs">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</p>
                            <div class="flex items-center mt-2">
                                <span class="badge-info">{{ ucfirst($event->type) }}</span>
                                <span class="text-xs text-gray-500 ml-auto">{{ \Carbon\Carbon::parse($event->start_date)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-sm text-gray-500">No upcoming events.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>