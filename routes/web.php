<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::view('/', 'home')->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('home');
    }

    if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if (method_exists($user, 'isTeacher') && $user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    }

    if (method_exists($user, 'isStudent') && $user->isStudent()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Students
    Route::get('/students', [AdminController::class, 'studentsIndex'])->name('students.index');
    Route::get('/students/create', [AdminController::class, 'studentsCreate'])->name('students.create');
    Route::post('/students', [AdminController::class, 'studentsStore'])->name('students.store');
    
    // Teachers - FIXED ORDER: specific routes before parameterized routes
    Route::get('/teachers', [AdminController::class, 'teachersIndex'])->name('teachers.index');
    Route::get('/teachers/create', [AdminController::class, 'teachersCreate'])->name('teachers.create');
    Route::post('/teachers', [AdminController::class, 'teachersStore'])->name('teachers.store');
    
    // Teacher Assignments - MUST come BEFORE teacher parameterized routes
    Route::get('/teachers/assignments', [AdminController::class, 'teacherAssignmentsIndex'])->name('teachers.assignments');
    Route::post('/teachers/assignments', [AdminController::class, 'teacherAssignmentsStore'])->name('teachers.assignments.store');
    Route::delete('/teachers/assignments/{assignment}', [AdminController::class, 'teacherAssignmentsDestroy'])->name('teachers.assignments.destroy');
    
    // Teacher parameterized routes
    Route::get('/teachers/{teacher}', [AdminController::class, 'teachersShow'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [AdminController::class, 'teachersEdit'])->name('teachers.edit');
    Route::match(['PUT', 'PATCH'], '/teachers/{teacher}', [AdminController::class, 'teachersUpdate'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [AdminController::class, 'teachersDestroy'])->name('teachers.destroy');
    
    // Classes
    Route::get('/classes', [AdminController::class, 'classesIndex'])->name('classes.index');
    Route::post('/classes', [AdminController::class, 'classesStore'])->name('classes.store');
    Route::get('/classes/{class}/edit', [AdminController::class, 'classesEdit'])->name('classes.edit');
    Route::match(['PUT', 'PATCH'], '/classes/{class}', [AdminController::class, 'classesUpdate'])->name('classes.update');
    Route::delete('/classes/{class}', [AdminController::class, 'classesDestroy'])->name('classes.destroy');
    
    // Sections
    Route::post('/classes/{class}/sections', [AdminController::class, 'sectionsStore'])->name('classes.sections.store');
    Route::match(['PUT', 'PATCH'], '/sections/{section}', [AdminController::class, 'sectionsUpdate'])->name('sections.update');
    Route::delete('/sections/{section}', [AdminController::class, 'sectionsDestroy'])->name('sections.destroy');
    
    // Attendance
    Route::get('/attendance', [AdminController::class, 'attendanceIndex'])->name('attendance.index');
    Route::post('/attendance', [AdminController::class, 'attendanceStore'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [AdminController::class, 'attendanceShow'])->name('attendance.show');
    Route::get('/attendance/today', [AdminController::class, 'attendanceToday'])->name('attendance.today');
    
    // Timetable
    Route::get('/timetable', [AdminController::class, 'timetableIndex'])->name('timetable.index');
    Route::get('/timetable/create', [AdminController::class, 'timetableCreate'])->name('timetable.create');
    Route::post('/timetable', [AdminController::class, 'timetableStore'])->name('timetable.store');
    Route::get('/timetable/{timetable}/edit', [AdminController::class, 'timetableEdit'])->name('timetable.edit');
    Route::match(['PUT', 'PATCH'], '/timetable/{timetable}', [AdminController::class, 'timetableUpdate'])->name('timetable.update');
    Route::delete('/timetable/{timetable}', [AdminController::class, 'timetableDestroy'])->name('timetable.destroy');
    Route::get('/timetable/sections/{classId}', [AdminController::class, 'getSectionsByClass'])->name('timetable.sections');
    
    // Notices
    Route::get('/notices', [AdminController::class, 'noticesIndex'])->name('notices.index');
    Route::get('/notices/create', [AdminController::class, 'noticesCreate'])->name('notices.create');
    
    // Fee
    Route::get('/fee-payments/create', [AdminController::class, 'feePaymentsCreate'])->name('fee-payments.create');
    Route::get('/fee-payments', [AdminController::class, 'feePaymentsIndex'])->name('fee-payments.index');
    Route::get('/fee-structures', [AdminController::class, 'feeStructuresIndex'])->name('fee-structures.index');
    
    // Exams & Results (TEMPORARY - use fallback views if methods don't exist)
    Route::get('/exams', [AdminController::class, 'examsIndex'])->name('exams.index');
    Route::get('/results', [AdminController::class, 'resultsIndex'])->name('results.index');
    
    // Other pages
    Route::get('/reports', [AdminController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/settings', [AdminController::class, 'settingsIndex'])->name('settings.index');
    Route::get('/events', [AdminController::class, 'eventsIndex'])->name('events.index');
});

// Teacher routes - simplified to avoid Livewire issues
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [\App\Http\Controllers\TeacherController::class, 'attendanceIndex'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\TeacherController::class, 'attendanceStore'])->name('attendance.store');
    
    // Temporary fallback for exam/results creation
    Route::get('/exams/create', function () {
        return view('teacher.exams.create');
    })->name('exams.create');
    
    Route::get('/results/create', function () {
        return view('teacher.results.create');
    })->name('results.create');
});

// Student routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [\App\Http\Controllers\StudentController::class, 'attendance'])->name('attendance');
    Route::get('/timetable', [\App\Http\Controllers\StudentController::class, 'timetable'])->name('timetable');
    Route::get('/assignments', [\App\Http\Controllers\StudentController::class, 'assignments'])->name('assignments');
    Route::get('/notices', [\App\Http\Controllers\StudentController::class, 'notices'])->name('notices');
    Route::get('/exams', [\App\Http\Controllers\StudentController::class, 'exams'])->name('exams');
    Route::get('/results', [\App\Http\Controllers\StudentController::class, 'results'])->name('results');
    Route::get('/report-card', [\App\Http\Controllers\StudentController::class, 'reportCard'])->name('report-card');
    Route::get('/fees', [\App\Http\Controllers\StudentController::class, 'fees'])->name('fees');
    Route::get('/library', [\App\Http\Controllers\StudentController::class, 'library'])->name('library');
    Route::get('/messages', [\App\Http\Controllers\StudentController::class, 'messages'])->name('messages');
});

// Shared routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-classes', function () {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('home');
        }

        if (method_exists($user, 'isTeacher') && $user->isTeacher()) {
            return view('teacher.classes');
        }

        if (method_exists($user, 'isStudent') && $user->isStudent()) {
            return view('student.classes');
        }

        abort(403);
    })->name('my-classes.index');

    Route::get('/assignments', function () {
        return view('teacher.assignments');
    })->name('assignments.index');

    Route::get('/my-results', function () {
        return view('student.results');
    })->name('my-results.index');

    Route::get('/child-results', function () {
        return view('parent.child-results');
    })->name('child-results.index');
});

// Admin resource aliases
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/students', function () {
        return redirect()->route('admin.students.index');
    })->name('students.index');
    
    Route::get('/teachers', function () {
        return redirect()->route('admin.teachers.index');
    })->name('teachers.index');
    
    Route::get('/classes', function () {
        return redirect()->route('admin.classes.index');
    })->name('classes.index');
    
    Route::get('/fee', function () {
        return redirect()->route('admin.fee-payments.index');
    })->name('fee.index');
    
    Route::get('/reports', function () {
        return redirect()->route('admin.reports.index');
    })->name('reports.index');
});

require __DIR__.'/auth.php';