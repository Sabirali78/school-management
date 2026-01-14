@extends('layouts.admin')

@section('title', 'Create Timetable Entry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Timetable Entry</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.timetable.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Timetable
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.timetable.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Class *</label>
                                    <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id">Section *</label>
                                    <select name="section_id" id="section_id" class="form-control @error('section_id') is-invalid @enderror" required disabled>
                                        <option value="">Select Class First</option>
                                    </select>
                                    @error('section_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Subject *</label>
                                    <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }} @if($subject->code)({{ $subject->code }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">Teacher *</label>
                                    <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="day">Day *</label>
                                    <select name="day" id="day" class="form-control @error('day') is-invalid @enderror" required>
                                        <option value="">Select Day</option>
                                        @foreach($days as $key => $value)
                                            <option value="{{ $key }}" {{ old('day') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_time">Start Time *</label>
                                    <input type="time" name="start_time" id="start_time" 
                                           class="form-control @error('start_time') is-invalid @enderror" 
                                           value="{{ old('start_time') }}" 
                                           required>
                                    @error('start_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_time">End Time *</label>
                                    <input type="time" name="end_time" id="end_time" 
                                           class="form-control @error('end_time') is-invalid @enderror" 
                                           value="{{ old('end_time') }}" 
                                           required>
                                    @error('end_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Create Timetable Entry
                        </button>
                        <button type="reset" class="btn btn-default">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Load sections when class is selected
        $('#class_id').change(function() {
            var classId = $(this).val();
            var sectionSelect = $('#section_id');
            
            if (classId) {
                sectionSelect.prop('disabled', false);
                
                $.ajax({
                    url: '{{ route("admin.timetable.sections", "") }}/' + classId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        sectionSelect.empty();
                        sectionSelect.append('<option value="">Select Section</option>');
                        
                        $.each(data, function(key, section) {
                            sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                        
                        @if(old('section_id'))
                            sectionSelect.val('{{ old("section_id") }}');
                        @endif
                    },
                    error: function() {
                        sectionSelect.empty();
                        sectionSelect.append('<option value="">Error loading sections</option>');
                    }
                });
            } else {
                sectionSelect.prop('disabled', true);
                sectionSelect.empty();
                sectionSelect.append('<option value="">Select Class First</option>');
            }
        });
        
        // Trigger change on page load if class is already selected (for validation errors)
        @if(old('class_id'))
            $('#class_id').trigger('change');
        @endif
        
        // Form validation
        $('form').validate({
            rules: {
                class_id: 'required',
                section_id: 'required',
                subject_id: 'required',
                teacher_id: 'required',
                day: 'required',
                start_time: 'required',
                end_time: {
                    required: true,
                    greaterThan: '#start_time'
                }
            },
            messages: {
                end_time: {
                    greaterThan: 'End time must be after start time'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        
        // Custom validation method for time comparison
        $.validator.addMethod("greaterThan", function(value, element, param) {
            var startTime = $(param).val();
            if (!startTime || !value) return true;
            
            var start = new Date('2000-01-01 ' + startTime);
            var end = new Date('2000-01-01 ' + value);
            return end > start;
        }, "End time must be after start time");
    });
</script>
@endpush