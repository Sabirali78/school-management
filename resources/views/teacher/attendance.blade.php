<x-teacher-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Mark Attendance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('teacher.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="form-label">Assignment</label>
                <select name="class_id" class="form-input">
                    <option value="">Select class</option>
                    @foreach($assignments as $a)
                        <option value="{{ $a->class_id }}" {{ request('class_id') == $a->class_id ? 'selected' : '' }}>{{ $a->class->name }} / {{ $a->section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Section</label>
                <select name="section_id" class="form-input">
                    <option value="">Select section</option>
                    @foreach($assignments as $a)
                        <option value="{{ $a->section_id }}" {{ request('section_id') == $a->section_id ? 'selected' : '' }}>{{ $a->section->name }} ({{ $a->class->name }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="form-input">
            </div>
            <div class="md:col-span-3 flex justify-end mt-2">
                <button class="btn-primary">Load Students</button>
            </div>
        </form>

        @if($students->count())
            <form method="POST" action="{{ route('teacher.attendance.store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                <input type="hidden" name="section_id" value="{{ request('section_id') }}">
                <input type="hidden" name="date" value="{{ request('date') }}">

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($students as $student)
                        <div class="flex items-center justify-between border-b py-2">
                            <div>
                                <div class="text-sm font-medium">{{ $student->roll_number }} — {{ $student->user->name }}</div>
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
                    <button type="submit" class="btn-primary">Save Attendance</button>
                </div>
            </form>
        @else
            <div class="text-sm text-gray-500">Select assignment and date, then click Load Students.</div>
        @endif
    </div>
</x-teacher-layout>
