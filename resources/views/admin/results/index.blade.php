<x-admin-layout>
    <x-slot name="title">
        Manage Results
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Results</h1>
                        <a href="{{ route('admin.results.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add Result
                        </a>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Student Name
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Roll #
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Exam
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Subject
                                    </th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs">
                                        Marks
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($results as $result)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            {{ $result->student->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            {{ $result->student->roll_number ?? 'N/A' }}
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            {{ $result->exam->name ?? 'N/A' }}
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            {{ $result->subject->name ?? 'N/A' }}
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            {{ $result->marks }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10 px-6">
                                            No results found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>