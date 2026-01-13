<x-teacher-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Teacher Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold mb-4">Your Assignments</h2>
            @if($assignments->count())
                <div class="space-y-3">
                    @foreach($assignments as $a)
                        <div class="flex items-center justify-between border-b py-2">
                            <div>
                                <div class="text-sm font-medium">{{ $a->class->name ?? 'N/A' }} — {{ $a->section->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Assigned on: {{ $a->created_at?->format('M d, Y') ?? '—' }}</div>
                            </div>
                            <div>
                                <a href="{{ route('teacher.attendance.index') }}?class_id={{ $a->class_id }}&section_id={{ $a->section_id }}&date={{ date('Y-m-d') }}" class="btn-primary">Mark Attendance</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500">You have no class assignments yet.</div>
            @endif

            <hr class="my-4" />

            <h3 class="font-semibold mb-2">Students in your assigned classes</h3>
            @if($students->count())
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($students as $st)
                        <div class="flex items-center justify-between border-b py-2">
                            <div>
                                <div class="text-sm font-medium">{{ $st->roll_number }} — {{ $st->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $st->user->email }}</div>
                            </div>
                            <div class="text-sm text-gray-500">{{ $st->class->name ?? '' }} / {{ $st->section->name ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500">No students found for your assignments.</div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold mb-4">Your Profile</h2>
            <div class="text-sm"><strong>Name:</strong> {{ $teacher->user->name ?? '' }}</div>
            <div class="text-sm"><strong>Email:</strong> {{ $teacher->user->email ?? '' }}</div>
            <div class="text-sm"><strong>Phone:</strong> {{ $teacher->phone ?? '—' }}</div>
            <div class="text-sm"><strong>Qualification:</strong> {{ $teacher->qualification ?? '—' }}</div>
        </div>
    </div>
</x-teacher-layout>


    <div class="p-6">
        <h1 class="text-2xl font-bold">Teacher Dashboard</h1>
    </div>

