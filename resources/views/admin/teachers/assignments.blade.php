<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Teacher Assignments</h1>
            <a href="{{ route('admin.teachers.index') }}" class="btn-secondary">All Teachers</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Assignments</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $a)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->teacher->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->class->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->section->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <form action="{{ route('admin.teachers.assignments.destroy', $a) }}" method="POST" onsubmit="return confirm('Remove assignment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No assignments yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Assign Class & Section</h2>
                <form action="{{ route('admin.teachers.assignments.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-input" required>
                            <option value="">Select teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->user->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-input" required>
                            <option value="">Select class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Section</label>
                        <select name="section_id" class="form-input" required>
                            <option value="">Select section</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button class="btn-primary">Assign</button>
                    </div>
                </form>

                <hr class="my-4" />

                <h3 class="font-semibold mb-2">View Students by Teacher</h3>
                <form method="GET" action="{{ route('admin.teachers.assignments') }}" class="space-y-3">
                    <div>
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-input">
                            <option value="">Select teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ (int)request('teacher_id') === $t->id ? 'selected' : '' }}>{{ $t->user->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Optional: Class</label>
                        <select name="class_id" class="form-input">
                            <option value="">Any</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Optional: Section</label>
                        <select name="section_id" class="form-input">
                            <option value="">Any</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button class="btn-secondary">Show Students</button>
                    </div>
                </form>

                @if($students && $students->count() > 0)
                    <hr class="my-4" />
                    <h3 class="font-semibold mb-2">Students</h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
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
                @elseif(request()->filled('teacher_id'))
                    <div class="mt-4 text-sm text-gray-500">No students found for selected teacher/filters.</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
