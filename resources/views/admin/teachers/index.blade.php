<x-admin-layout>
    <div class="p-6">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Teachers Management</h1>
            <a href="{{ route('admin.teachers.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                + Add New Teacher
            </a>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <form method="GET" action="{{ route('admin.teachers.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by name, email, phone, or qualification..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Qualification Filter -->
                    <div>
                        <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                        <select name="qualification" 
                                id="qualification"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Qualifications</option>
                            @foreach($qualifications as $qualification)
                                <option value="{{ $qualification }}" {{ request('qualification') == $qualification ? 'selected' : '' }}>
                                    {{ $qualification }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teachers Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($teachers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qualification
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created At
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($teachers as $teacher)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        #{{ $teacher->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $teacher->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $teacher->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $teacher->phone ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $teacher->qualification ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $teacher->created_at?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.teachers.show', $teacher) }}" 
                                               class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                               class="text-green-600 hover:text-green-900">Edit</a>
                                            <form action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $teachers->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No teachers found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'qualification']))
                            Try adjusting your search filters.
                        @else
                            Get started by creating a new teacher.
                        @endif
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('admin.teachers.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            + Add New Teacher
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Total Teachers</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $teachers->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Showing</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $teachers->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Qualifications</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $qualifications->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for enhanced filtering -->
    <script>
        // Auto-submit form when select changes (optional)
        document.getElementById('qualification').addEventListener('change', function() {
            this.form.submit();
        });

        // Debounced search for better UX
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
</x-admin-layout>