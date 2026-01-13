<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('home');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (! $teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $assignments = TeacherAssignment::with(['class','section'])
            ->where('teacher_id', $teacher->id)
            ->get();

        // gather students for all assigned class-section pairs
        $students = collect();
        if ($assignments->count() > 0) {
            $students = Student::with('user','class','section')
                ->where(function($q) use ($assignments) {
                    foreach ($assignments as $a) {
                        $q->orWhere(function($sq) use ($a) {
                            $sq->where('class_id', $a->class_id)
                               ->where('section_id', $a->section_id);
                        });
                    }
                })->orderBy('roll_number')->get();
        }

        return view('teacher.dashboard', compact('teacher','assignments','students'));
    }

    public function attendanceIndex(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        if (! $teacher) abort(403);

        $assignments = TeacherAssignment::with(['class','section'])
            ->where('teacher_id', $teacher->id)->get();

        $students = collect();
        $detailsMap = [];
        $attendance = null;

        if ($request->filled('class_id') && $request->filled('section_id') && $request->filled('date')) {
            // check teacher is assigned to this class/section
            $allowed = $assignments->firstWhere('class_id', $request->input('class_id')) && $assignments->firstWhere('section_id', $request->input('section_id'));
            if (! $allowed) {
                return back()->withErrors(['error' => 'You are not assigned to this class/section.']);
            }

            $students = Student::with('user')
                ->where('class_id', $request->input('class_id'))
                ->where('section_id', $request->input('section_id'))
                ->orderBy('roll_number')
                ->get();

            $attendance = Attendance::where('class_id', $request->input('class_id'))
                ->where('section_id', $request->input('section_id'))
                ->where('date', $request->input('date'))
                ->first();

            if ($attendance) {
                $details = AttendanceDetail::where('attendance_id', $attendance->id)->get();
                foreach ($details as $d) {
                    $detailsMap[$d->student_id] = $d->status;
                }
            }
        }

        return view('teacher.attendance', compact('assignments','students','detailsMap','attendance'));
    }

    public function attendanceStore(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        if (! $teacher) abort(403);

        $data = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'status' => 'nullable|array',
        ]);

        $assignments = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $data['class_id'])->where('section_id', $data['section_id'])->first();
        if (! $assignments) {
            return back()->withErrors(['error' => 'You are not assigned to this class/section.']);
        }

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
            return redirect()->route('teacher.attendance.index', ['class_id'=>$data['class_id'],'section_id'=>$data['section_id'],'date'=>$data['date']])->with('success','Attendance saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Unable to save attendance: '.$e->getMessage()]);
        }
    }
}
