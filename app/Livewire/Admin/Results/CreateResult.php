<?php

namespace App\Livewire\Admin\Results;

use Livewire\Component;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Result;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class CreateResult extends Component
{
    public $students;
    public $subjects;
    public $exams;

    public $student_id;
    public $subject_id;
    public $exam_id;
    public $marks;

    public function mount()
    {
        // Query students and their related user names
        $this->students = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name . ' (' . $student->roll_number . ')',
            ];
        });
        
        $this->subjects = Subject::all();
        $this->exams = Exam::all();
    }

    public function save()
    {
        $this->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_id' => 'required|exists:exams,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        Result::create([
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'exam_id' => $this->exam_id,
            'marks' => $this->marks,
        ]);

        session()->flash('message', 'Result created successfully.');

        $this->reset(['student_id', 'subject_id', 'exam_id', 'marks']);
    }

    public function render()
    {
        return view('livewire.admin.results.create-result');
    }
}
