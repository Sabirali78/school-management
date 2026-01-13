<x-student-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">My Attendance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('student.attendance') }}" class="flex space-x-3 mb-4">
            <input type="date" name="from" class="form-input" value="{{ request('from') }}">
            <input type="date" name="to" class="form-input" value="{{ request('to') }}">
            <button class="btn-primary">Filter</button>
        </form>

        @if($records->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($records as $r)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $r->date }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ ucfirst($r->status) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $r->class_id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $r->section_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-sm text-gray-500">No attendance records found.</div>
        @endif
    </div>
</x-student-layout>
