<x-admin-layout>
    <div class="p-6 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Edit Teacher</h1>

        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input name="phone" value="{{ old('phone', $teacher->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md" />
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Qualification</label>
                <input name="qualification" value="{{ old('qualification', $teacher->qualification) }}" class="mt-1 block w-full border-gray-300 rounded-md" />
            </div>

            <div class="flex items-center space-x-2">
                <button class="btn-primary">Save</button>
                <a href="{{ route('admin.teachers.index') }}" class="text-sm text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
