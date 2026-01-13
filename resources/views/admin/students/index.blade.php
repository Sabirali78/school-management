<x-admin-layout>
    <div class="p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Students Management</h1>
                <p class="text-gray-600 mt-1">View and manage all students in the school</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <a href="{{ route('admin.students.create') }}" class="btn-primary">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Student
                </a>
                <button class="btn-secondary">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $students->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeCount ?? $students->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Classes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $classCount ?? '10' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Avg Attendance</p>
                        <p class="text-2xl font-bold text-gray-900">94%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-input" placeholder="Name, Roll No, Email...">
                </div>
                
                <!-- Class Filter -->
                <div>
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-input">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    </select>
                </div>
                
                <!-- Gender Filter -->
                <div>
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-input">
                        <option value="">All Gender</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="md:col-span-4 flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.students.index') }}" class="btn-secondary">
                        Clear Filters
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Students List</h2>
                <div class="text-sm text-gray-500">
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class & Roll No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Attendance
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                            <tr data-id="{{ $student->id }}" class="hover:bg-gray-50 transition">
                                <!-- Student Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Class & Roll No -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->class->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->section->name ?? 'N/A' }}</div>
                                    <div class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block mt-1">
                                        {{ $student->roll_number }}
                                    </div>
                                </td>
                                
                                <!-- Contact -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->phone ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                                    @if($student->address)
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $student->address }}</div>
                                    @endif
                                </td>
                                
                                <!-- Attendance -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">94%</div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 94%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        Active
                                    </span>
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-900" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-green-600 hover:text-green-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-purple-600 hover:text-purple-900" title="More">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new student.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.students.create') }}" class="btn-primary">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add New Student
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Bulk Actions -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <select class="form-input w-48">
                        <option value="">Bulk Actions</option>
                        <option value="export">Export Selected</option>
                        <option value="promote">Promote to Next Class</option>
                        <option value="inactive">Mark as Inactive</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button class="btn-secondary">Apply</button>
                </div>
                <div class="text-sm text-gray-500">
                    <a href="#" class="text-blue-600 hover:text-blue-800">Download Sample CSV</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800">Bulk Import</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quick actions for student rows
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) {
                        // Handle row click (view details)
                        const studentId = this.dataset.id;
                        if (studentId) {
                            window.location.href = `/admin/students/${studentId}`;
                        }
                    }
                });
            });
        });
    </script>
</x-admin-layout>