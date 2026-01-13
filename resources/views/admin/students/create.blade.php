<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Students — Create</h1>
            <a href="{{ route('admin.students.index') }}" class="btn-secondary">Back to list</a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-input" required>
                            <option value="">Select class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Section</label>
                        <select name="section_id" class="form-input" required>
                            <option value="">Select section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Roll Number</label>
                        <input type="text" name="roll_number" value="{{ old('roll_number') }}" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-input" rows="3">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.students.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Create Student</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
