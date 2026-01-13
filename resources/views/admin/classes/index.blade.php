<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Classes & Sections — Manage</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Classes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sections</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($classes as $class)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $class->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $class->level }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        @foreach($class->sections as $s)
                                            <div class="inline-block mr-2 px-2 py-1 bg-gray-100 rounded text-xs">{{ $s->name }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <a href="{{ route('admin.classes.index', ['class_id' => $class->id]) }}" class="text-blue-600 mr-3">Manage</a>
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="text-green-600 mr-3">Edit</a>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No classes found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                @if($selectedClass)
                    <h2 class="font-semibold mb-4">Manage Sections for {{ $selectedClass->name }}</h2>
                    <div class="mb-4">
                        @foreach($sections as $section)
                            <div class="flex items-center justify-between border-b py-2">
                                <div>
                                    <div class="text-sm font-medium">{{ $section->name }}</div>
                                    <div class="text-xs text-gray-500">Capacity: {{ $section->capacity ?? '—' }}</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.sections.update', $section) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $section->name }}" class="form-input text-xs" />
                                        <input type="number" name="capacity" value="{{ $section->capacity }}" class="form-input text-xs w-20" />
                                        <button class="btn-secondary text-xs">Save</button>
                                    </form>
                                    <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this section?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 text-xs">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h3 class="font-medium">Add Section</h3>
                    <form action="{{ route('admin.classes.sections.store', $selectedClass) }}" method="POST" class="space-y-3 mt-3">
                        @csrf
                        <div>
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-input">
                        </div>
                        <div class="flex justify-end">
                            <button class="btn-primary">Add Section</button>
                        </div>
                    </form>
                @else
                    <h2 class="font-semibold mb-4">Create Class</h2>
                    <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Level</label>
                            <input type="text" name="level" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="3"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button class="btn-primary">Create Class</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
