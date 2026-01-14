<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\FeePayment;
use App\Models\Notice;
use App\Models\Event;
use App\Models\User;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Timetable;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Section;
use App\Models\TeacherAssignment;
use App\Models\FeeStructure;
use App\Models\Report;
use App\Models\Setting;
use App\Models\ActivityLog;





class AdminController extends Controller
{
public function index()
{
    try {
        \Log::info('Dashboard loading started');
        
        $totalStudents = Student::count();
        \Log::info('Total students counted: ' . $totalStudents);
        
        $totalTeachers = Teacher::count();
        \Log::info('Total teachers counted: ' . $totalTeachers);
        
        // Today's attendance
        $today = now()->toDateString();
        \Log::info('Today date: ' . $today);
        
        $attendanceIds = Attendance::where('date', $today)->pluck('id');
        \Log::info('Attendance IDs found: ' . $attendanceIds->count());
        
        $presentCount = AttendanceDetail::whereIn('attendance_id', $attendanceIds)
            ->where('status', 'present')
            ->count();
        \Log::info('Present count: ' . $presentCount);
        
        // Add more debug logs for each query...
        
        return view('admin.dashboard', compact(...));
        
    } catch (\Exception $e) {
        // Remove the Log call temporarily to see if logging is the issue
        // \Log::error('Dashboard index error', [...]);
        
        return view('admin.dashboard')
            ->with('error', 'Error: ' . $e->getMessage());
    }
}

// Stub methods to satisfy named routes used in the admin UI while designing
public function noticesIndex()
{
    return view('admin.notices.index');
}

public function attendanceToday()
{
    return view('admin.attendance.today');
}

public function feePaymentsCreate()
{
    return view('admin.fee-payments.create');
}

public function noticesCreate()
{
    return view('admin.notices.create');
}

public function studentsIndex(Request $request)
{
    try {
        $query = Student::with(['user', 'class', 'section']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('roll_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        $students = $query->orderBy('id', 'desc')->paginate(20);

        // classes for filter dropdown
        $classes = \App\Models\ClassModel::orderBy('name')->get();

        // optional counts used in dashboard cards
        $classCount = $classes->count();
        // No `status` column on students table in migrations — use total students as active count fallback
        $activeCount = Student::count();

        return view('admin.students.index', compact('students', 'classes', 'classCount', 'activeCount'));
        
    } catch (\Exception $e) {
        \Log::error('Students index error', [
            'message' => $e->getMessage(),
            'request' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? "Error loading students: " . $e->getMessage()
                : "Unable to load students. Please try again."
        );
    }
}

public function studentsCreate()
{
    try {
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.students.create', compact('classes', 'sections'));
        
    } catch (\Exception $e) {
        \Log::error('Students create form error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.students.index')->with('error', 'Unable to load student creation form.');
    }
}

public function studentsStore(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required|exists:sections,id',
        'dob' => 'required|date',
        'roll_number' => 'required|string|unique:students,roll_number',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:1000',
    ]);

    DB::beginTransaction();
    try {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'Student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'dob' => $data['dob'],
            'roll_number' => $data['roll_number'],
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
        ]);

        DB::commit();
        
        \Log::info('Student created successfully', [
            'student_id' => $student->id,
            'user_id' => $user->id,
            'roll_number' => $student->roll_number
        ]);
        
        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Student creation failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->except('password', 'password_confirmation'),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to create student: ' . $e->getMessage()
                    : 'Unable to create student. Please check the data and try again.'
            );
    }
}


public function classesIndex()
{
    try {
        // load classes and optionally a selected class for managing sections
        $classes = \App\Models\ClassModel::with('sections')->orderBy('name')->get();
        $selectedClass = null;
        $sections = collect();
        
        if (request()->filled('class_id')) {
            $selectedClass = \App\Models\ClassModel::with('sections')->find(request('class_id'));
            if ($selectedClass) {
                $sections = $selectedClass->sections()->orderBy('name')->get();
            } else {
                \Log::warning('Class not found', ['class_id' => request('class_id')]);
            }
        }

        return view('admin.classes.index', compact('classes', 'selectedClass', 'sections'));
        
    } catch (\Exception $e) {
        \Log::error('Classes index error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 'Unable to load classes. Please try again.');
    }
}

public function classesStore(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255|unique:classes,name',
        'level' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        $class = \App\Models\ClassModel::create($data);
        
        \Log::info('Class created successfully', [
            'class_id' => $class->id,
            'name' => $class->name
        ]);
        
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Class creation failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to create class: ' . $e->getMessage()
                    : 'Unable to create class. Please try again.'
            );
    }
}

public function classesEdit(\App\Models\ClassModel $class)
{
    try {
        return view('admin.classes.edit', compact('class'));
        
    } catch (\Exception $e) {
        \Log::error('Class edit form error', [
            'message' => $e->getMessage(),
            'class_id' => $class->id ?? 'unknown',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.classes.index')->with('error', 'Unable to load class edit form.');
    }
}

public function classesUpdate(Request $request, \App\Models\ClassModel $class)
{
    $data = $request->validate([
        'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
        'level' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        $class->update($data);
        
        \Log::info('Class updated successfully', [
            'class_id' => $class->id,
            'name' => $class->name
        ]);
        
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Class update failed', [
            'message' => $e->getMessage(),
            'class_id' => $class->id,
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to update class: ' . $e->getMessage()
                    : 'Unable to update class. Please try again.'
            );
    }
}

public function classesDestroy(\App\Models\ClassModel $class)
{
    try {
        $classId = $class->id;
        $className = $class->name;
        $class->delete();
        
        \Log::info('Class deleted successfully', [
            'class_id' => $classId,
            'name' => $className
        ]);
        
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Class deletion failed', [
            'message' => $e->getMessage(),
            'class_id' => $class->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? 'Unable to delete class: ' . $e->getMessage()
                : 'Unable to delete class. It may have associated data.'
        );
    }
}

// Sections
public function sectionsStore(Request $request, \App\Models\ClassModel $class)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'capacity' => 'nullable|integer|min:0',
    ]);

    try {
        $section = $class->sections()->create($data);
        
        \Log::info('Section created successfully', [
            'section_id' => $section->id,
            'class_id' => $class->id,
            'name' => $section->name
        ]);
        
        return redirect()->route('admin.classes.index', ['class_id' => $class->id])->with('success', 'Section added successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Section creation failed', [
            'message' => $e->getMessage(),
            'class_id' => $class->id,
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Unable to add section. Please try again.');
    }
}

public function sectionsUpdate(Request $request, \App\Models\Section $section)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'capacity' => 'nullable|integer|min:0',
    ]);

    try {
        $section->update($data);
        
        \Log::info('Section updated successfully', [
            'section_id' => $section->id,
            'class_id' => $section->class_id,
            'name' => $section->name
        ]);
        
        return redirect()->route('admin.classes.index', ['class_id' => $section->class_id])->with('success', 'Section updated successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Section update failed', [
            'message' => $e->getMessage(),
            'section_id' => $section->id,
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Unable to update section. Please try again.');
    }
}

public function sectionsDestroy(\App\Models\Section $section)
{
    try {
        $classId = $section->class_id;
        $sectionId = $section->id;
        $sectionName = $section->name;
        $section->delete();
        
        \Log::info('Section deleted successfully', [
            'section_id' => $sectionId,
            'class_id' => $classId,
            'name' => $sectionName
        ]);
        
        return redirect()->route('admin.classes.index', ['class_id' => $classId])->with('success', 'Section deleted successfully.');
        
    } catch (\Exception $e) {
        \Log::error('Section deletion failed', [
            'message' => $e->getMessage(),
            'section_id' => $section->id,
            'class_id' => $section->class_id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 'Unable to delete section. It may have associated data.');
    }
}

public function attendanceIndex(Request $request)
{
    try {
        // load classes/sections for filters
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();

        // list existing attendance records with optional filters
        $query = Attendance::with(['class', 'section'])->orderBy('date', 'desc');
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->input('section_id'));
        }
        if ($request->filled('date')) {
            $query->where('date', $request->input('date'));
        }

        $attendances = $query->paginate(20);

        $students = null;
        $attendance = null;
        $detailsMap = [];

        // If class/section/date provided, fetch students to mark attendance
        if ($request->filled('class_id') && $request->filled('section_id') && $request->filled('date')) {
            $classId = $request->input('class_id');
            $sectionId = $request->input('section_id');
            $date = $request->input('date');

            \Log::debug('Fetching attendance data', [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'date' => $date
            ]);

            $students = Student::where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->with('user')
                ->orderBy('roll_number')
                ->get();

            $attendance = Attendance::where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('date', $date)
                ->first();

            if ($attendance) {
                $details = AttendanceDetail::where('attendance_id', $attendance->id)->get();
                foreach ($details as $d) {
                    $detailsMap[$d->student_id] = $d->status;
                }
            }
            
            \Log::debug('Attendance data loaded', [
                'student_count' => $students->count(),
                'attendance_record' => $attendance ? 'found' : 'not found',
                'details_count' => count($detailsMap)
            ]);
        }

        return view('admin.attendance.index', compact('classes', 'sections', 'attendances', 'students', 'attendance', 'detailsMap'));
        
    } catch (\Exception $e) {
        \Log::error('Attendance index error', [
            'message' => $e->getMessage(),
            'request' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? "Error loading attendance: " . $e->getMessage()
                : "Unable to load attendance. Please try again."
        );
    }
}

public function attendanceStore(Request $request)
{
    $data = $request->validate([
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required|exists:sections,id',
        'date' => 'required|date',
        'status' => 'nullable|array',
    ]);

    DB::beginTransaction();
    try {
        \Log::info('Creating/Updating attendance', [
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'date' => $data['date'],
            'status_count' => count($request->input('status', []))
        ]);

        $attendance = Attendance::firstOrCreate([
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'date' => $data['date'],
        ]);

        $statuses = $request->input('status', []);
        $processedCount = 0;
        
        foreach ($statuses as $studentId => $status) {
            DB::table('attendance_details')->updateOrInsert(
                ['attendance_id' => $attendance->id, 'student_id' => $studentId],
                ['status' => $status]
            );
            $processedCount++;
        }

        DB::commit();
        
        \Log::info('Attendance saved successfully', [
            'attendance_id' => $attendance->id,
            'processed_students' => $processedCount
        ]);
        
        return redirect()
            ->route('admin.attendance.index', [
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'date' => $data['date']
            ])
            ->with('success', 'Attendance saved successfully.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Attendance save failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->except('status'),
            'status_count' => count($request->input('status', [])),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to save attendance: ' . $e->getMessage()
                    : 'Unable to save attendance. Please try again.'
            );
    }
}

public function attendanceShow(Attendance $attendance)
{
    try {
        $attendance->load(['class', 'section']);
        $details = AttendanceDetail::where('attendance_id', $attendance->id)
            ->with('student.user')
            ->get();
            
        \Log::debug('Showing attendance', [
            'attendance_id' => $attendance->id,
            'details_count' => $details->count()
        ]);
        
        return view('admin.attendance.show', compact('attendance', 'details'));
        
    } catch (\Exception $e) {
        \Log::error('Attendance show error', [
            'message' => $e->getMessage(),
            'attendance_id' => $attendance->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 'Unable to load attendance details.');
    }
}

  public function examsIndex()
{
    try {
        $exams = Exam::orderBy('exam_date', 'desc')->paginate(20);
        
        \Log::debug('Exams index loaded', [
            'exam_count' => $exams->total(),
            'page' => $exams->currentPage()
        ]);
        
        return view('admin.exams.index', compact('exams'));
        
    } catch (\Exception $e) {
        \Log::error('Exams index error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return view('admin.exams.index', ['exams' => collect()])
            ->with('error', 'Unable to load exams. Please try again.');
    }
}

public function resultsIndex()
{
    try {
        $results = Result::with(['student.user', 'exam', 'subject'])
            ->orderBy('id', 'desc')
            ->paginate(20);
            
        \Log::debug('Results index loaded', [
            'result_count' => $results->total(),
            'page' => $results->currentPage()
        ]);
        
        return view('admin.results.index', compact('results'));
        
    } catch (\Exception $e) {
        \Log::error('Results index error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return view('admin.results.index', ['results' => collect()])
            ->with('error', 'Unable to load results. Please try again.');
    }
}

public function feeStructuresIndex()
{
    return view('admin.fee-structures.index');
}

public function feePaymentsIndex()
{
    return view('admin.fee-payments.index');
}

public function reportsIndex()
{
    return view('admin.reports.index');
}

public function settingsIndex()
{
    return view('admin.settings.index');
}

public function eventsIndex()
{
    return view('admin.events.index');
}

public function teachersStore(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'phone' => 'nullable|string|max:50',
        'qualification' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        \Log::info('Creating teacher', [
            'email' => $data['email'],
            'name' => $data['name']
        ]);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'Teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'phone' => $data['phone'] ?? '',
            'qualification' => $data['qualification'] ?? '',
        ]);

        DB::commit();
        
        \Log::info('Teacher created successfully', [
            'teacher_id' => $teacher->id,
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Teacher creation failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->except('password', 'password_confirmation'),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to create teacher: ' . $e->getMessage()
                    : 'Unable to create teacher. Please check the data and try again.'
            );
    }
}

public function teachersShow(Teacher $teacher)
{
    try {
        $teacher->load(['user', 'assignments.class', 'assignments.section']);
        
        \Log::debug('Showing teacher', [
            'teacher_id' => $teacher->id,
            'assignments_count' => $teacher->assignments->count()
        ]);
        
        return view('admin.teachers.show', compact('teacher'));
        
    } catch (\Exception $e) {
        \Log::error('Teacher show error', [
            'message' => $e->getMessage(),
            'teacher_id' => $teacher->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('error', 'Unable to load teacher details.');
    }
}

// Teacher assignments: list, assign, view students
public function teacherAssignmentsIndex(Request $request)
{
    try {
        $teachers = Teacher::with('user')->orderBy('id','desc')->get();
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();

        $assignments = \App\Models\TeacherAssignment::with(['teacher.user','class','section'])
            ->orderBy('id','desc')
            ->get();

        $selectedTeacherId = $request->input('teacher_id');
        $selectedAssignment = null;
        $students = collect();

        if ($request->filled('assignment_id')) {
            $selectedAssignment = \App\Models\TeacherAssignment::with(['class','section'])
                ->find($request->input('assignment_id'));
                
            if (!$selectedAssignment) {
                \Log::warning('Assignment not found', [
                    'assignment_id' => $request->input('assignment_id')
                ]);
            }
        }

        if ($selectedTeacherId) {
            \Log::debug('Filtering students for teacher', [
                'teacher_id' => $selectedTeacherId,
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id')
            ]);
            
            $query = \App\Models\Student::with('user');
            
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->input('class_id'));
            }
            
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->input('section_id'));
            }
            
            // restrict to assignments of the selected teacher if available
            $teacherAssigns = \App\Models\TeacherAssignment::where('teacher_id', $selectedTeacherId)->get();
            
            if ($teacherAssigns->count() > 0 && !$request->filled('class_id') && !$request->filled('section_id')) {
                // gather class/section pairs and show students for those
                $classIds = $teacherAssigns->pluck('class_id')->unique();
                $sectionIds = $teacherAssigns->pluck('section_id')->unique();
                
                \Log::debug('Using teacher assignments filter', [
                    'class_ids' => $classIds->toArray(),
                    'section_ids' => $sectionIds->toArray()
                ]);
                
                $query->whereIn('class_id', $classIds)
                      ->whereIn('section_id', $sectionIds);
            }
            
            $students = $query->orderBy('roll_number')->get();
            
            \Log::debug('Students retrieved', [
                'student_count' => $students->count()
            ]);
        }

        return view('admin.teachers.assignments', compact(
            'teachers',
            'classes',
            'sections',
            'assignments',
            'students',
            'selectedTeacherId',
            'selectedAssignment'
        ));
        
    } catch (\Exception $e) {
        \Log::error('Teacher assignments index error', [
            'message' => $e->getMessage(),
            'request' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? 'Error loading teacher assignments: ' . $e->getMessage()
                : 'Unable to load teacher assignments. Please try again.'
        );
    }
}

public function teacherAssignmentsStore(Request $request)
{
    $data = $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required|exists:sections,id',
    ]);

    try {
        \Log::info('Creating teacher assignment', [
            'teacher_id' => $data['teacher_id'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id']
        ]);
        
        // Check for existing assignment
        $existing = \App\Models\TeacherAssignment::where($data)->first();
        
        if ($existing) {
            \Log::warning('Teacher assignment already exists', [
                'assignment_id' => $existing->id
            ]);
            
            return redirect()->route('admin.teachers.assignments')
                ->with('warning', 'This assignment already exists.');
        }
        
        $assignment = \App\Models\TeacherAssignment::create($data);
        
        \Log::info('Teacher assignment created', [
            'assignment_id' => $assignment->id
        ]);
        
        return redirect()->route('admin.teachers.assignments')
            ->with('success', 'Class/section assigned to teacher successfully.');
            
    } catch (\Exception $e) {
        \Log::error('Teacher assignment creation failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to create assignment: ' . $e->getMessage()
                    : 'Unable to create assignment. Please try again.'
            );
    }
}

public function teacherAssignmentsDestroy(\App\Models\TeacherAssignment $assignment)
{
    try {
        $assignmentId = $assignment->id;
        $teacherId = $assignment->teacher_id;
        
        \Log::info('Deleting teacher assignment', [
            'assignment_id' => $assignmentId,
            'teacher_id' => $teacherId
        ]);
        
        $assignment->delete();
        
        \Log::info('Teacher assignment deleted', [
            'assignment_id' => $assignmentId
        ]);
        
        return redirect()->route('admin.teachers.assignments')
            ->with('success', 'Assignment removed successfully.');
            
    } catch (\Exception $e) {
        \Log::error('Teacher assignment deletion failed', [
            'message' => $e->getMessage(),
            'assignment_id' => $assignment->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? 'Unable to delete assignment: ' . $e->getMessage()
                : 'Unable to delete assignment. It may have associated data.'
        );
    }
}

public function teachersEdit(Teacher $teacher)
{
    try {
        $teacher->load('user');
        
        \Log::debug('Loading teacher edit form', [
            'teacher_id' => $teacher->id
        ]);
        
        return view('admin.teachers.edit', compact('teacher'));
        
    } catch (\Exception $e) {
        \Log::error('Teacher edit form error', [
            'message' => $e->getMessage(),
            'teacher_id' => $teacher->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('error', 'Unable to load teacher edit form.');
    }
}

public function teachersUpdate(Request $request, Teacher $teacher)
{
    $data = $request->validate([
        'phone' => 'nullable|string|max:50',
        'qualification' => 'nullable|string|max:255',
        // Optionally add user fields if you want to update them too
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $teacher->user_id,
    ]);

    DB::beginTransaction();
    try {
        \Log::info('Updating teacher', [
            'teacher_id' => $teacher->id,
            'fields' => array_keys($data)
        ]);
        
        // Update teacher fields
        $teacher->update([
            'phone' => $data['phone'] ?? $teacher->phone,
            'qualification' => $data['qualification'] ?? $teacher->qualification,
        ]);
        
        // Update user fields if provided
        if (isset($data['name']) || isset($data['email'])) {
            $userData = [];
            if (isset($data['name'])) $userData['name'] = $data['name'];
            if (isset($data['email'])) $userData['email'] = $data['email'];
            
            $teacher->user()->update($userData);
        }
        
        DB::commit();
        
        \Log::info('Teacher updated successfully', [
            'teacher_id' => $teacher->id
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Teacher update failed', [
            'message' => $e->getMessage(),
            'teacher_id' => $teacher->id,
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to update teacher: ' . $e->getMessage()
                    : 'Unable to update teacher. Please try again.'
            );
    }
}

public function teachersDestroy(Teacher $teacher)
{
    DB::beginTransaction();
    try {
        $teacherId = $teacher->id;
        $userId = $teacher->user_id;
        
        \Log::warning('Deleting teacher', [
            'teacher_id' => $teacherId,
            'user_id' => $userId
        ]);
        
        // Optional: Check if teacher has associated records before deletion
        $hasAssignments = \App\Models\TeacherAssignment::where('teacher_id', $teacherId)->exists();
        
        if ($hasAssignments) {
            \Log::warning('Teacher has assignments', [
                'teacher_id' => $teacherId
            ]);
            
            return back()->with('error', 
                'Cannot delete teacher. Please remove all class/section assignments first.'
            );
        }
        
        // Remove teacher profile
        $teacher->delete();
        
        // Optional: Also delete user account
        // User::where('id', $userId)->delete();
        
        DB::commit();
        
        \Log::info('Teacher deleted successfully', [
            'teacher_id' => $teacherId
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Teacher deletion failed', [
            'message' => $e->getMessage(),
            'teacher_id' => $teacher->id,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? 'Unable to delete teacher: ' . $e->getMessage()
                : 'Unable to delete teacher. Please try again.'
        );
    }
}

// Teacher
public function teachersIndex(Request $request)
{
    try {
        // Start query with eager loading of user relationship
        $query = Teacher::with('user');
        
        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('phone', 'like', '%' . $search . '%')
                  ->orWhere('qualification', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter by qualification
        if ($request->filled('qualification')) {
            $query->where('qualification', $request->input('qualification'));
        }
        
        // Get unique qualifications for filter dropdown
        $qualifications = Teacher::select('qualification')
            ->distinct()
            ->whereNotNull('qualification')
            ->orderBy('qualification')
            ->pluck('qualification');
        
        \Log::debug('Loading teachers index', [
            'search' => $request->input('search'),
            'qualification_filter' => $request->input('qualification'),
            'qualification_count' => $qualifications->count()
        ]);
        
        // Order by teacher's id (descending)
        $teachers = $query->orderBy('id', 'desc')->paginate(20);
        
        \Log::debug('Teachers loaded', [
            'total_teachers' => $teachers->total(),
            'current_page' => $teachers->currentPage(),
            'per_page' => $teachers->perPage()
        ]);
        
        return view('admin.teachers.index', compact('teachers', 'qualifications'));
        
    } catch (\Exception $e) {
        \Log::error('Teachers index error', [
            'message' => $e->getMessage(),
            'request' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return view('admin.teachers.index', [
            'teachers' => collect(),
            'qualifications' => collect()
        ])->with('error', 
            config('app.debug') 
                ? 'Error loading teachers: ' . $e->getMessage()
                : 'Unable to load teachers. Please try again.'
        );
    }
}

public function teachersCreate()
{
    try {
        return view('admin.teachers.create');
        
    } catch (\Exception $e) {
        \Log::error('Teacher create form error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.teachers.index')
            ->with('error', 'Unable to load teacher creation form.');
    }
}


    // Add these methods to your AdminController class:

/**
 * Display timetable index page with filtering
 */
public function timetableIndex(Request $request)
{
    try {
        \Log::debug('Loading timetable index', [
            'filters' => $request->only(['class_id', 'section_id', 'day']),
            'user_id' => auth()->id()
        ]);

        // Get filter parameters
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $day = $request->input('day');

        // Start query with relationships
        $query = Timetable::with(['class', 'section', 'subject', 'teacher.user']);

        // Apply filters
        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        if ($day) {
            $query->where('day', $day);
        }

        // Get timetables grouped by class and section for better display
        $timetables = $query->orderBy('class_id')
            ->orderBy('section_id')
            ->orderByRaw("FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('start_time')
            ->get();

        // Group timetables by class and section for display
        $groupedTimetables = $timetables->groupBy(function($item) {
            return $item->class_id . '-' . $item->section_id;
        });

        // Get data for dropdowns
        $classes = ClassModel::with('sections')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        
        // Days for filter dropdown
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];

        \Log::debug('Timetable index data loaded', [
            'timetable_count' => $timetables->count(),
            'grouped_count' => $groupedTimetables->count(),
            'class_count' => $classes->count(),
            'subject_count' => $subjects->count(),
            'teacher_count' => $teachers->count()
        ]);

        return view('admin.timetable.index', compact(
            'groupedTimetables',
            'classes',
            'subjects',
            'teachers',
            'days',
            'classId',
            'sectionId',
            'day'
        ));
        
    } catch (\Exception $e) {
        \Log::error('Timetable index error', [
            'message' => $e->getMessage(),
            'request' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return view('admin.timetable.index', [
            'groupedTimetables' => collect(),
            'classes' => collect(),
            'subjects' => collect(),
            'teachers' => collect(),
            'days' => [
                'monday' => 'Monday',
                'tuesday' => 'Tuesday', 
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday'
            ],
            'classId' => null,
            'sectionId' => null,
            'day' => null
        ])->with('error', 
            config('app.debug') 
                ? 'Error loading timetable: ' . $e->getMessage()
                : 'Unable to load timetable. Please try again.'
        );
    }
}

/**
 * Show form to create new timetable entry
 */
public function timetableCreate()
{
    try {
        $classes = ClassModel::with('sections')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];

        \Log::debug('Loading timetable create form', [
            'class_count' => $classes->count(),
            'subject_count' => $subjects->count(),
            'teacher_count' => $teachers->count()
        ]);

        return view('admin.timetable.create', compact('classes', 'subjects', 'teachers', 'days'));
        
    } catch (\Exception $e) {
        \Log::error('Timetable create form error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.timetable.index')
            ->with('error', 'Unable to load timetable creation form.');
    }
}

/**
 * Store new timetable entry
 */
public function timetableStore(Request $request)
{
    $validated = $request->validate([
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required|exists:sections,id',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'required|exists:teachers,id',
        'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    DB::beginTransaction();
    try {
        \Log::info('Creating timetable entry', [
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'teacher_id' => $validated['teacher_id'],
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'user_id' => auth()->id()
        ]);

        // Check for overlapping timetables
        $overlap = Timetable::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('day', $validated['day'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($overlap) {
            \Log::warning('Timetable overlap detected', [
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id'],
                'day' => $validated['day'],
                'time_slot' => $validated['start_time'] . '-' . $validated['end_time']
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'A class is already scheduled during this time slot!']);
        }

        // Check if teacher is available
        $teacherBusy = Timetable::where('teacher_id', $validated['teacher_id'])
            ->where('day', $validated['day'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($teacherBusy) {
            \Log::warning('Teacher already assigned', [
                'teacher_id' => $validated['teacher_id'],
                'day' => $validated['day'],
                'time_slot' => $validated['start_time'] . '-' . $validated['end_time']
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'The teacher is already assigned to another class during this time!']);
        }

        $timetable = Timetable::create($validated);
        
        DB::commit();

        \Log::info('Timetable entry created successfully', [
            'timetable_id' => $timetable->id,
            'class_id' => $timetable->class_id,
            'section_id' => $timetable->section_id
        ]);

        return redirect()->route('admin.timetable.index')
            ->with('success', 'Timetable entry created successfully!');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        
        \Log::warning('Timetable validation failed', [
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        
        return back()
            ->withInput()
            ->withErrors($e->errors());
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Timetable creation failed', [
            'message' => $e->getMessage(),
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to create timetable: ' . $e->getMessage()
                    : 'Unable to create timetable entry. Please try again.'
            );
    }
}

/**
 * Show form to edit timetable entry
 */
public function timetableEdit(Timetable $timetable)
{
    try {
        $timetable->load(['class', 'section', 'subject', 'teacher.user']);
        
        $classes = ClassModel::with('sections')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];

        \Log::debug('Loading timetable edit form', [
            'timetable_id' => $timetable->id,
            'class_id' => $timetable->class_id,
            'section_id' => $timetable->section_id
        ]);

        return view('admin.timetable.edit', compact('timetable', 'classes', 'subjects', 'teachers', 'days'));
        
    } catch (\Exception $e) {
        \Log::error('Timetable edit form error', [
            'message' => $e->getMessage(),
            'timetable_id' => $timetable->id ?? 'unknown',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return redirect()->route('admin.timetable.index')
            ->with('error', 'Unable to load timetable edit form.');
    }
}

/**
 * Update timetable entry
 */
public function timetableUpdate(Request $request, Timetable $timetable)
{
    $validated = $request->validate([
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required|exists:sections,id',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'required|exists:teachers,id',
        'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    DB::beginTransaction();
    try {
        \Log::info('Updating timetable entry', [
            'timetable_id' => $timetable->id,
            'old_data' => [
                'class_id' => $timetable->class_id,
                'section_id' => $timetable->section_id,
                'teacher_id' => $timetable->teacher_id,
                'day' => $timetable->day,
                'start_time' => $timetable->start_time,
                'end_time' => $timetable->end_time
            ],
            'new_data' => $validated,
            'user_id' => auth()->id()
        ]);

        // Check for overlapping timetables (excluding current one)
        $overlap = Timetable::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('day', $validated['day'])
            ->where('id', '!=', $timetable->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($overlap) {
            \Log::warning('Timetable overlap detected during update', [
                'timetable_id' => $timetable->id,
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id'],
                'day' => $validated['day'],
                'time_slot' => $validated['start_time'] . '-' . $validated['end_time']
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'A class is already scheduled during this time slot!']);
        }

        // Check if teacher is available (excluding current entry)
        $teacherBusy = Timetable::where('teacher_id', $validated['teacher_id'])
            ->where('day', $validated['day'])
            ->where('id', '!=', $timetable->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($teacherBusy) {
            \Log::warning('Teacher already assigned during update', [
                'timetable_id' => $timetable->id,
                'teacher_id' => $validated['teacher_id'],
                'day' => $validated['day'],
                'time_slot' => $validated['start_time'] . '-' . $validated['end_time']
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'The teacher is already assigned to another class during this time!']);
        }

        $timetable->update($validated);
        
        DB::commit();

        \Log::info('Timetable entry updated successfully', [
            'timetable_id' => $timetable->id,
            'changes' => $validated
        ]);

        return redirect()->route('admin.timetable.index')
            ->with('success', 'Timetable entry updated successfully!');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        
        \Log::warning('Timetable update validation failed', [
            'timetable_id' => $timetable->id,
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        
        return back()
            ->withInput()
            ->withErrors($e->errors());
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Timetable update failed', [
            'message' => $e->getMessage(),
            'timetable_id' => $timetable->id,
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 
                config('app.debug') 
                    ? 'Unable to update timetable: ' . $e->getMessage()
                    : 'Unable to update timetable entry. Please try again.'
            );
    }
}

/**
 * Delete timetable entry
 */
public function timetableDestroy(Timetable $timetable)
{
    DB::beginTransaction();
    try {
        \Log::warning('Deleting timetable entry', [
            'timetable_id' => $timetable->id,
            'class_id' => $timetable->class_id,
            'section_id' => $timetable->section_id,
            'teacher_id' => $timetable->teacher_id,
            'day' => $timetable->day,
            'time_slot' => $timetable->start_time . '-' . $timetable->end_time,
            'user_id' => auth()->id()
        ]);

        $timetable->delete();
        
        DB::commit();

        \Log::info('Timetable entry deleted successfully', [
            'timetable_id' => $timetable->id
        ]);

        return redirect()->route('admin.timetable.index')
            ->with('success', 'Timetable entry deleted successfully!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Timetable deletion failed', [
            'message' => $e->getMessage(),
            'timetable_id' => $timetable->id,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 
            config('app.debug') 
                ? 'Unable to delete timetable: ' . $e->getMessage()
                : 'Unable to delete timetable entry. Please try again.'
        );
    }
}

/**
 * Get sections by class ID (for AJAX)
 */
public function getSectionsByClass($classId)
{
    try {
        \Log::debug('Fetching sections for class', ['class_id' => $classId]);
        
        $sections = Section::where('class_id', $classId)->get();
        
        \Log::debug('Sections retrieved', [
            'class_id' => $classId,
            'section_count' => $sections->count()
        ]);
        
        return response()->json($sections);
        
    } catch (\Exception $e) {
        \Log::error('Failed to fetch sections', [
            'message' => $e->getMessage(),
            'class_id' => $classId,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'error' => 'Unable to fetch sections',
            'message' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
}

