<x-student-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Assignments</h1>
        <p class="text-sm text-gray-600">Pending and recent assignments</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if(count($assignments))
            <ul class="space-y-3">
                @foreach($assignments as $a)
                    <li class="border p-3 rounded-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $a['title'] }}</div>
                                <div class="text-xs text-gray-500">Due: {{ $a['due'] }}</div>
                            </div>
                            <a href="#" class="text-sm text-blue-600">View</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500">No assignments found.</div>
        @endif
    </div>
</x-student-layout>
