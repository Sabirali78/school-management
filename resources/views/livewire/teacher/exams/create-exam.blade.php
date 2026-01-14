<div>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Exam</h1>
                <p class="text-gray-600">Add a new exam schedule to the system.</p>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <div class="card bg-white p-6 rounded-lg shadow">
            <div class="card-header mb-4">
                <h3 class="text-lg font-semibold text-gray-900">New Exam Form</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Exam Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                            <input type="text" id="name" wire:model="name" class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md" placeholder="e.g., Mid-Term Exams">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Exam Date -->
                        <div>
                            <label for="exam_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" id="exam_date" wire:model="exam_date" class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                            @error('exam_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn-primary">
                            Save Exam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
