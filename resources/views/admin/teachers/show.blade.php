<x-admin-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold">Teacher — Details</h1>
        <div class="mt-4 bg-white p-4 rounded-lg shadow">
            <p><strong>Name:</strong> {{ $teacher->user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $teacher->user->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
            <p><strong>Qualification:</strong> {{ $teacher->qualification ?? 'N/A' }}</p>
            <p><strong>Created:</strong> {{ $teacher->created_at?->format('M d, Y') ?? '—' }}</p>
        </div>
    </div>
</x-admin-layout>
