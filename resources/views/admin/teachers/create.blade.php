<x-admin-layout>
    <div class="p-6 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Create Teacher</h1>

        @if($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md" required />
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md" required />
            </div>

            <div class="mb-3 grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-md" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-md" required />
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input name="phone" value="{{ old('phone') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Qualification</label>
                <input name="qualification" value="{{ old('qualification') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
            </div>

            <div class="flex items-center space-x-2">
                <button class="btn-primary">Create Teacher</button>
                <a href="{{ route('admin.teachers.index') }}" class="text-sm text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
