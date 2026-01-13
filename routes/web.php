<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AdminController;



Route::view('/', 'home')->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    if (! $user) {
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

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Notices
    Route::get('/admin/notices', [AdminController::class, 'noticesIndex'])->name('admin.notices.index');
    Route::get('/admin/notices/create', [AdminController::class, 'noticesCreate'])->name('admin.notices.create');

    // Attendance
    Route::get('/admin/attendance/today', [AdminController::class, 'attendanceToday'])->name('admin.attendance.today');
    Route::get('/admin/attendance', [AdminController::class, 'attendanceIndex'])->name('admin.attendance.index');
    Route::post('/admin/attendance', [AdminController::class, 'attendanceStore'])->name('admin.attendance.store');
    Route::get('/admin/attendance/{attendance}', [AdminController::class, 'attendanceShow'])->name('admin.attendance.show');

    // Fee payments
    Route::get('/admin/fee-payments/create', [AdminController::class, 'feePaymentsCreate'])->name('admin.fee-payments.create');

    // Students
    Route::get('/admin/students', [AdminController::class, 'studentsIndex'])->name('admin.students.index');
    Route::get('/admin/students/create', [AdminController::class, 'studentsCreate'])->name('admin.students.create');
    Route::post('/admin/students', [AdminController::class, 'studentsStore'])->name('admin.students.store');

    // Teachers
    Route::get('/admin/teachers', [AdminController::class, 'teachersIndex'])->name('admin.teachers.index');
    Route::get('/admin/teachers/create', [AdminController::class, 'teachersCreate'])->name('admin.teachers.create');
    Route::post('/admin/teachers', [AdminController::class, 'teachersStore'])->name('admin.teachers.store');
    // Teacher Assignments (place before parameterized teacher routes)
    Route::get('/admin/teachers/assignments', [AdminController::class, 'teacherAssignmentsIndex'])->name('admin.teachers.assignments');
    Route::post('/admin/teachers/assignments', [AdminController::class, 'teacherAssignmentsStore'])->name('admin.teachers.assignments.store');
    Route::delete('/admin/teachers/assignments/{assignment}', [AdminController::class, 'teacherAssignmentsDestroy'])->name('admin.teachers.assignments.destroy');

    Route::get('/admin/teachers/{teacher}', [AdminController::class, 'teachersShow'])->name('admin.teachers.show');
    Route::get('/admin/teachers/{teacher}/edit', [AdminController::class, 'teachersEdit'])->name('admin.teachers.edit');
    Route::match(['PUT','PATCH'],'/admin/teachers/{teacher}', [AdminController::class, 'teachersUpdate'])->name('admin.teachers.update');
    Route::delete('/admin/teachers/{teacher}', [AdminController::class, 'teachersDestroy'])->name('admin.teachers.destroy');
    
    // Teacher Assignments
    Route::get('/admin/teachers/assignments', [AdminController::class, 'teacherAssignmentsIndex'])->name('admin.teachers.assignments');
    Route::post('/admin/teachers/assignments', [AdminController::class, 'teacherAssignmentsStore'])->name('admin.teachers.assignments.store');
    Route::delete('/admin/teachers/assignments/{assignment}', [AdminController::class, 'teacherAssignmentsDestroy'])->name('admin.teachers.assignments.destroy');

    // Classes
    Route::get('/admin/classes', [AdminController::class, 'classesIndex'])->name('admin.classes.index');
    Route::post('/admin/classes', [AdminController::class, 'classesStore'])->name('admin.classes.store');
    Route::get('/admin/classes/{class}/edit', [AdminController::class, 'classesEdit'])->name('admin.classes.edit');
    Route::match(['PUT','PATCH'],'/admin/classes/{class}', [AdminController::class, 'classesUpdate'])->name('admin.classes.update');
    Route::delete('/admin/classes/{class}', [AdminController::class, 'classesDestroy'])->name('admin.classes.destroy');

    // Sections nested under classes
    Route::post('/admin/classes/{class}/sections', [AdminController::class, 'sectionsStore'])->name('admin.classes.sections.store');
    Route::match(['PUT','PATCH'],'/admin/sections/{section}', [AdminController::class, 'sectionsUpdate'])->name('admin.sections.update');
    Route::delete('/admin/sections/{section}', [AdminController::class, 'sectionsDestroy'])->name('admin.sections.destroy');

    // Attendance Index
    Route::get('/admin/attendance', [AdminController::class, 'attendanceIndex'])->name('admin.attendance.index');

    // Exams & Results
    Route::get('/admin/exams', [AdminController::class, 'examsIndex'])->name('admin.exams.index');
    Route::get('/admin/results', [AdminController::class, 'resultsIndex'])->name('admin.results.index');

    // Fee structures & payments
    Route::get('/admin/fee-structures', [AdminController::class, 'feeStructuresIndex'])->name('admin.fee-structures.index');
    Route::get('/admin/fee-payments', [AdminController::class, 'feePaymentsIndex'])->name('admin.fee-payments.index');

    // Timetable
    Route::get('/admin/timetable', [AdminController::class, 'timetableIndex'])->name('admin.timetable.index');

    // Reports
    Route::get('/admin/reports', [AdminController::class, 'reportsIndex'])->name('admin.reports.index');

    // Settings
    Route::get('/admin/settings', [AdminController::class, 'settingsIndex'])->name('admin.settings.index');

    // Events
    Route::get('/admin/events', [AdminController::class, 'eventsIndex'])->name('admin.events.index');
});

// Teacher and Student dashboards (simple placeholders)
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('teacher.dashboard');

    // Teacher attendance pages
    Route::get('/teacher/attendance', [\App\Http\Controllers\TeacherController::class, 'attendanceIndex'])->name('teacher.attendance.index');
    Route::post('/teacher/attendance', [\App\Http\Controllers\TeacherController::class, 'attendanceStore'])->name('teacher.attendance.store');

    Route::get('/student/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/attendance', [\App\Http\Controllers\StudentController::class, 'attendance'])->name('student.attendance');
    Route::get('/student/timetable', [\App\Http\Controllers\StudentController::class, 'timetable'])->name('student.timetable');
    Route::get('/student/assignments', [\App\Http\Controllers\StudentController::class, 'assignments'])->name('student.assignments');
    Route::get('/student/notices', [\App\Http\Controllers\StudentController::class, 'notices'])->name('student.notices');
    Route::get('/student/exams', [\App\Http\Controllers\StudentController::class, 'exams'])->name('student.exams');
    Route::get('/student/results', [\App\Http\Controllers\StudentController::class, 'results'])->name('student.results');
    Route::get('/student/report-card', [\App\Http\Controllers\StudentController::class, 'reportCard'])->name('student.report-card');
    Route::get('/student/fees', [\App\Http\Controllers\StudentController::class, 'fees'])->name('student.fees');
    Route::get('/student/library', [\App\Http\Controllers\StudentController::class, 'library'])->name('student.library');
    Route::get('/student/messages', [\App\Http\Controllers\StudentController::class, 'messages'])->name('student.messages');

    // Shared 'my classes' route for teachers and students
    Route::get('/my-classes', function () {
        $user = auth()->user();
        if (! $user) {
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

// Aliases for admin resources used in the header (redirect to admin namespaced routes)
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/students', function () { return redirect()->route('admin.students.index'); })->name('students.index');
    Route::get('/teachers', function () { return redirect()->route('admin.teachers.index'); })->name('teachers.index');
    Route::get('/classes', function () { return redirect()->route('admin.classes.index'); })->name('classes.index');
    Route::get('/fee', function () { return redirect()->route('admin.fee-payments.index'); })->name('fee.index');
    Route::get('/reports', function () { return redirect()->route('admin.reports.index'); })->name('reports.index');
});

require __DIR__.'/auth.php';
