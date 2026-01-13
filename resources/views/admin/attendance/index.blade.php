<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Attendance — Manage</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-input">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Section</label>
                    <select name="section_id" class="form-input">
                        <option value="">All Sections</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="form-input">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary">Load</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Attendance Records</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $a)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->date }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->class->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->section->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <a href="{{ route('admin.attendance.show', $a) }}" class="text-blue-600">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No attendance records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Mark Attendance</h2>

                @if($students)
                    <form method="POST" action="{{ route('admin.attendance.store') }}">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="section_id" value="{{ request('section_id') }}">
                        <input type="hidden" name="date" value="{{ request('date') }}">

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($students as $student)
                                <div class="flex items-center justify-between border-b py-2">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->roll_number }} — {{ $student->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->user->email }}</div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @php $current = $detailsMap[$student->id] ?? 'present'; @endphp
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="radio" name="status[{{ $student->id }}]" value="present" {{ $current == 'present' ? 'checked' : '' }}>
                                            <span class="text-sm text-green-700">Present</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="radio" name="status[{{ $student->id }}]" value="absent" {{ $current == 'absent' ? 'checked' : '' }}>
                                            <span class="text-sm text-red-700">Absent</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="radio" name="status[{{ $student->id }}]" value="late" {{ $current == 'late' ? 'checked' : '' }}>
                                            <span class="text-sm text-yellow-700">Late</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex justify-end space-x-3">
                            <a href="{{ route('admin.attendance.index') }}" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary">Save Attendance</button>
                        </div>
                    </form>
                @else
                    <div class="text-sm text-gray-500">Select class, section and date, then click "Load" to mark attendance.</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
