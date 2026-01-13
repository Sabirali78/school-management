<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Attendance — {{ $attendance->date }}</h1>
            <a href="{{ route('admin.attendance.index') }}" class="btn-secondary">Back</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <strong>Class:</strong> {{ $attendance->class->name ?? 'N/A' }}
                <span class="mx-4"><strong>Section:</strong> {{ $attendance->section->name ?? 'N/A' }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Roll</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($details as $d)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $d->student->roll_number ?? '' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $d->student->user->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $d->status == 'present' ? 'bg-green-100 text-green-800' : ($d->status == 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($d->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
