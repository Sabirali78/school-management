<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\AttendanceDetail;
use App\Models\Attendance;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('home');

        $student = Student::with('user','class','section')->where('user_id', $user->id)->first();
        if (! $student) {
            abort(403, 'Student profile not found');
        }

        $attendanceDetails = AttendanceDetail::where('student_id', $student->id)
            ->join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->select('attendance_details.*', 'attendances.date')
            ->orderBy('attendances.date', 'desc')
            ->take(20)
            ->get();

        $total = AttendanceDetail::where('student_id', $student->id)->count();
        $present = AttendanceDetail::where('student_id', $student->id)->where('status','present')->count();
        $attendancePercent = $total > 0 ? round(($present / $total) * 100, 1) : null;

        return view('student.dashboard', compact('student','attendanceDetails','total','present','attendancePercent'));
    }

    public function attendance(Request $request)
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();

        $query = AttendanceDetail::where('student_id', $student->id)
            ->join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->select('attendance_details.*', 'attendances.date', 'attendances.class_id', 'attendances.section_id')
            ->orderBy('attendances.date', 'desc');

        if ($request->filled('from')) {
            $query->where('attendances.date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->where('attendances.date', '<=', $request->input('to'));
        }

        $records = $query->get();

        return view('student.attendance', compact('student','records'));
    }

    public function timetable()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();

        // Minimal timetable data for display — can be replaced with real timetable source
        $timetable = [
            ['time' => '08:00 - 09:00', 'subject' => 'Mathematics', 'room' => '205', 'teacher' => 'Mr. A'],
            ['time' => '09:15 - 10:15', 'subject' => 'Science', 'room' => '207', 'teacher' => 'Ms. B'],
            ['time' => '10:30 - 11:30', 'subject' => 'English', 'room' => '203', 'teacher' => 'Mrs. C'],
        ];

        return view('student.timetable', compact('student','timetable'));
    }

    public function assignments()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();

        // Placeholder assignments; replace with real data source later
        $assignments = [
            ['title' => 'Math Homework 1', 'due' => now()->addDays(2)->toDateString()],
            ['title' => 'Science Project', 'due' => now()->addDays(5)->toDateString()],
        ];

        return view('student.assignments', compact('student','assignments'));
    }

    public function notices()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        // placeholder notices
        $notices = [
            ['title' => 'School closed on Friday', 'time' => '2 hours ago'],
            ['title' => 'Parent meeting next Monday', 'time' => '1 day ago'],
        ];
        return view('student.notices', compact('student','notices'));
    }

    public function exams()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.exams', compact('student'));
    }

    public function results()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.results', compact('student'));
    }

    public function reportCard()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.report-card', compact('student'));
    }

    public function fees()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.fees', compact('student'));
    }

    public function library()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.library', compact('student'));
    }

    public function messages()
    {
        $user = Auth::user();
        $student = Student::with('user','class','section')->where('user_id', $user->id)->firstOrFail();
        return view('student.messages', compact('student'));
    }
}
