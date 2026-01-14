@extends('layouts.admin')

@section('title', 'Timetable Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timetable Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.timetable.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Timetable Entry
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.timetable.index') }}" class="form-inline">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <select name="class_id" class="form-control w-100" onchange="this.form.submit()">
                                            <option value="">All Classes</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select name="section_id" class="form-control w-100" onchange="this.form.submit()">
                                            <option value="">All Sections</option>
                                            @if(request('class_id'))
                                                @php
                                                    $selectedClass = $classes->firstWhere('id', request('class_id'));
                                                @endphp
                                                @if($selectedClass)
                                                    @foreach($selectedClass->sections as $section)
                                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                            {{ $section->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select name="day" class="form-control w-100" onchange="this.form.submit()">
                                            <option value="">All Days</option>
                                            @foreach($days as $key => $value)
                                                <option value="{{ $key }}" {{ request('day') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($groupedTimetables->isEmpty())
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> No Timetable Found!</h5>
                            <p>No timetable entries found for the selected filters. You can create a new timetable entry by clicking the "Add Timetable Entry" button.</p>
                            <a href="{{ route('admin.timetable.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Your First Timetable
                            </a>
                        </div>
                    @else
                        @foreach($groupedTimetables as $groupKey => $timetableGroup)
                            @php
                                $firstEntry = $timetableGroup->first();
                                $class = $firstEntry->class;
                                $section = $firstEntry->section;
                            @endphp
                            
                            <div class="card card-primary mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-calendar-alt"></i> 
                                        Timetable for {{ $class->name }} - {{ $section->name }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Time</th>
                                                    <th>Subject</th>
                                                    <th>Teacher</th>
                                                    <th>Duration</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timetableGroup as $timetable)
                                                    <tr>
                                                        <td style="min-width: 120px;">
                                                            <strong>{{ ucfirst($timetable->day) }}</strong>
                                                        </td>
                                                        <td style="min-width: 150px;">
                                                            {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }} 
                                                            - 
                                                            {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-info">{{ $timetable->subject->name }}</span>
                                                            @if($timetable->subject->code)
                                                                <small class="text-muted">({{ $timetable->subject->code }})</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="mr-2">
                                                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                                                </div>
                                                                <div>
                                                                    {{ $timetable->teacher->user->name }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $start = \Carbon\Carbon::parse($timetable->start_time);
                                                                $end = \Carbon\Carbon::parse($timetable->end_time);
                                                                $duration = $start->diff($end)->format('%H:%I');
                                                            @endphp
                                                            {{ $duration }} hours
                                                        </td>
                                                        <td style="min-width: 150px;">
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.timetable.edit', $timetable) }}" 
                                                                   class="btn btn-warning" 
                                                                   title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('admin.timetable.destroy', $timetable) }}" 
                                                                      method="POST" 
                                                                      class="d-inline"
                                                                      onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update sections dropdown when class is changed
    document.querySelector('select[name="class_id"]').addEventListener('change', function() {
        const classId = this.value;
        const sectionSelect = document.querySelector('select[name="section_id"]');
        
        if (classId) {
            fetch(`/admin/classes/${classId}/sections`)
                .then(response => response.json())
                .then(sections => {
                    sectionSelect.innerHTML = '<option value="">All Sections</option>';
                    sections.forEach(section => {
                        sectionSelect.innerHTML += `<option value="${section.id}">${section.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                });
        } else {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
        }
    });
</script>
@endpush