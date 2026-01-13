<x-student-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Notices</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if(count($notices))
            <ul class="space-y-3">
                @foreach($notices as $n)
                    <li class="border p-3 rounded-md">
                        <div class="font-medium">{{ $n['title'] }}</div>
                        <div class="text-xs text-gray-500">{{ $n['time'] }}</div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500">No notices found.</div>
        @endif
    </div>
</x-student-layout>
