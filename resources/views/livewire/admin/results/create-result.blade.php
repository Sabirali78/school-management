<div>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Result</h1>
                <p class="text-gray-600">Enter marks for a student for a specific exam and subject.</p>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Result Entry Form</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Exam -->
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam</label>
                            <select id="exam_id" wire:model="exam_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Select an Exam</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>
                            @error('exam_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select id="subject_id" wire:model="subject_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Select a Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Student -->
                        <div class="md:col-span-2">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select id="student_id" wire:model="student_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Select a Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student['id'] }}">{{ $student['name'] }}</option>
                                @endforeach
                            </select>
                            @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Marks -->
                        <div>
                            <label for="marks" class="block text-sm font-medium text-gray-700">Marks</label>
                            <input type="number" id="marks" wire:model="marks" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md" placeholder="Enter marks">
                            @error('marks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn-primary">
                            Save Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
