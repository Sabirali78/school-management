<x-student-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Full Timetable</h1>
        <p class="text-sm text-gray-600">{{ $student->class->name ?? 'Class' }} • {{ $student->section->name ?? 'Section' }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left">
            <thead>
                <tr class="text-sm text-gray-600">
                    <th class="py-2">Time</th>
                    <th class="py-2">Subject</th>
                    <th class="py-2">Room</th>
                    <th class="py-2">Teacher</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timetable as $row)
                <tr class="border-t">
                    <td class="py-3">{{ $row['time'] }}</td>
                    <td class="py-3">{{ $row['subject'] }}</td>
                    <td class="py-3">{{ $row['room'] }}</td>
                    <td class="py-3">{{ $row['teacher'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-student-layout>
