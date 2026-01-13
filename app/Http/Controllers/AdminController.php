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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();

        // Today's attendance: find today's attendance record(s) and count presents
        $today = now()->toDateString();
        $attendanceIds = Attendance::where('date', $today)->pluck('id');
        $presentCount = AttendanceDetail::whereIn('attendance_id', $attendanceIds)
            ->where('status', 'present')
            ->count();

        $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0;

        // Fee collection for current month
        $collected = FeePayment::where('status', 'paid')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('paid_amount');

        $pending = FeePayment::where('status', 'pending')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('paid_amount');

        $collectionPercent = ($collected + $pending) > 0 ? round(($collected / ($collected + $pending)) * 100) : 0;

        // Recent notices, recent students, upcoming events
        $recentNotices = Notice::latest()->take(3)->get();
        // students table doesn't include timestamps (no created_at), order by id instead
        $recentStudents = Student::orderBy('id', 'desc')->take(5)->get();
        $upcomingEvents = Event::where('start_date', '>=', $today)->orderBy('start_date')->take(3)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'presentCount',
            'attendancePercentage',
            'collected',
            'collectionPercent',
            'recentNotices',
            'recentStudents',
            'upcomingEvents'
        ));
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
    }

    public function studentsCreate()
    {
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('admin.students.create', compact('classes', 'sections'));
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
            return redirect()->route('admin.students.index')->with('success', 'Student created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Unable to create student: ' . $e->getMessage()]);
        }
    }


    public function classesIndex()
    {
        // load classes and optionally a selected class for managing sections
        $classes = \App\Models\ClassModel::with('sections')->orderBy('name')->get();
        $selectedClass = null;
        $sections = collect();
        if (request()->filled('class_id')) {
            $selectedClass = \App\Models\ClassModel::with('sections')->find(request('class_id'));
            if ($selectedClass) {
                $sections = $selectedClass->sections()->orderBy('name')->get();
            }
        }

        return view('admin.classes.index', compact('classes', 'selectedClass', 'sections'));
    }

    public function classesStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'level' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);

        $class = \App\Models\ClassModel::create($data);
        return redirect()->route('admin.classes.index')->with('success', 'Class created.');
    }

    public function classesEdit(\App\Models\ClassModel $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function classesUpdate(Request $request, \App\Models\ClassModel $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'level' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);

        $class->update($data);
        return redirect()->route('admin.classes.index')->with('success', 'Class updated.');
    }

    public function classesDestroy(\App\Models\ClassModel $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted.');
    }

    // Sections
    public function sectionsStore(Request $request, \App\Models\ClassModel $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
        ]);

        $class->sections()->create($data);
        return redirect()->route('admin.classes.index', ['class_id' => $class->id])->with('success', 'Section added.');
    }

    public function sectionsUpdate(Request $request, \App\Models\Section $section)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
        ]);

        $section->update($data);
        return redirect()->route('admin.classes.index', ['class_id' => $section->class_id])->with('success', 'Section updated.');
    }

    public function sectionsDestroy(\App\Models\Section $section)
    {
        $classId = $section->class_id;
        $section->delete();
        return redirect()->route('admin.classes.index', ['class_id' => $classId])->with('success', 'Section deleted.');
    }

    public function attendanceIndex(Request $request)
    {
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
        }

        return view('admin.attendance.index', compact('classes', 'sections', 'attendances', 'students', 'attendance', 'detailsMap'));
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
            $attendance = Attendance::firstOrCreate([
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'date' => $data['date'],
            ]);

            $statuses = $request->input('status', []);
            foreach ($statuses as $studentId => $status) {
                DB::table('attendance_details')->updateOrInsert(
                    ['attendance_id' => $attendance->id, 'student_id' => $studentId],
                    ['status' => $status]
                );
            }

            DB::commit();
            return redirect()->route('admin.attendance.index', ['class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'date' => $data['date']])->with('success', 'Attendance saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Unable to save attendance: ' . $e->getMessage()]);
        }
    }

    public function attendanceShow(Attendance $attendance)
    {
        $attendance->load(['class', 'section']);
        $details = AttendanceDetail::where('attendance_id', $attendance->id)->with('student.user')->get();
        return view('admin.attendance.show', compact('attendance', 'details'));
    }

    public function examsIndex()
    {
        return view('admin.exams.index');
    }

    public function resultsIndex()
    {
        return view('admin.results.index');
    }

    public function feeStructuresIndex()
    {
        return view('admin.fee-structures.index');
    }

    public function feePaymentsIndex()
    {
        return view('admin.fee-payments.index');
    }

    public function timetableIndex()
    {
        return view('admin.timetable.index');
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
            return redirect()->route('admin.teachers.index')->with('success', 'Teacher created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Unable to create teacher: ' . $e->getMessage()]);
        }
    }

    public function teachersShow(Teacher $teacher)
    {
        return view('admin.teachers.show', compact('teacher'));
    }

    // Teacher assignments: list, assign, view students
    public function teacherAssignmentsIndex(Request $request)
    {
        $teachers = Teacher::with('user')->orderBy('id','desc')->get();
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();

        $assignments = \App\Models\TeacherAssignment::with(['teacher.user','class','section'])->orderBy('id','desc')->get();

        $selectedTeacherId = $request->input('teacher_id');
        $selectedAssignment = null;
        $students = collect();

        if ($request->filled('assignment_id')) {
            $selectedAssignment = \App\Models\TeacherAssignment::with(['class','section'])->find($request->input('assignment_id'));
        }

        if ($selectedTeacherId) {
            // show students for first assignment of teacher or filtered by class/section
            $query = \App\Models\Student::with('user');
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->input('class_id'));
            }
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->input('section_id'));
            }
            // restrict to assignments of the selected teacher if available
            $teacherAssigns = \App\Models\TeacherAssignment::where('teacher_id', $selectedTeacherId)->get();
            if ($teacherAssigns->count() > 0 && ! $request->filled('class_id') && ! $request->filled('section_id')) {
                // gather class/section pairs and show students for those
                $classIds = $teacherAssigns->pluck('class_id')->unique();
                $sectionIds = $teacherAssigns->pluck('section_id')->unique();
                $query->whereIn('class_id', $classIds)->whereIn('section_id', $sectionIds);
            }
            $students = $query->orderBy('roll_number')->get();
        }

        return view('admin.teachers.assignments', compact('teachers','classes','sections','assignments','students','selectedTeacherId','selectedAssignment'));
    }

    public function teacherAssignmentsStore(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $assignment = \App\Models\TeacherAssignment::firstOrCreate($data);
        return redirect()->route('admin.teachers.assignments')->with('success','Assigned class/section to teacher.');
    }

    public function teacherAssignmentsDestroy(\App\Models\TeacherAssignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.teachers.assignments')->with('success','Assignment removed.');
    }

    public function teachersEdit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function teachersUpdate(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'phone' => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:255',
        ]);

        $teacher->update([
            'phone' => $data['phone'] ?? $teacher->phone,
            'qualification' => $data['qualification'] ?? $teacher->qualification,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated.');
    }

    public function teachersDestroy(Teacher $teacher)
    {
        // Remove teacher profile and optionally the user
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted.');
    }

    // Teacher
    public function teachersIndex(Request $request)
{
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
    
    // FIX: Order by user's created_at or teacher's id instead
    // Option 1: Order by teacher's id (descending)
    $teachers = $query->orderBy('id', 'desc')->paginate(20);
    
    // Option 2: If you want to order by user's created_at (requires join)
    // $teachers = $query->join('users', 'teachers.user_id', '=', 'users.id')
    //                   ->orderBy('users.created_at', 'desc')
    //                   ->select('teachers.*')
    //                   ->paginate(20);
    
    return view('admin.teachers.index', compact('teachers', 'qualifications'));
}    public function teachersCreate()
    {
        return view('admin.teachers.create');
    }

}
