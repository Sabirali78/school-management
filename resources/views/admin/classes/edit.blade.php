<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edit Class — {{ $class->name }}</h1>
            <a href="{{ route('admin.classes.index') }}" class="btn-secondary">Back</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ $class->name }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Level</label>
                        <input type="text" name="level" value="{{ $class->level }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3">{{ $class->description }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button class="btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
