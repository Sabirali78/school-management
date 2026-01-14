<x-admin-layout>
    <x-slot name="title">
        Manage Exams
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Exams</h1>
                        <a href="{{ route('admin.exams.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Exam
                        </a>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        ID
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Exam Name
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($exams as $exam)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $exam->id }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $exam->name }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $exam->exam_date->format('d M, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10 px-6">
                                            No exams found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $exams->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>