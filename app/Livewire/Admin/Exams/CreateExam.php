<?php

namespace App\Livewire\Admin\Exams;

use Livewire\Component;
use App\Models\Exam;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class CreateExam extends Component
{
    public $name;
    public $exam_date;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'exam_date' => 'required|date',
        ]);

        Exam::create([
            'name' => $this->name,
            'exam_date' => $this->exam_date,
        ]);

        session()->flash('message', 'Exam created successfully.');

        $this->reset(['name', 'exam_date']);
    }

    public function render()
    {
        return view('livewire.admin.exams.create-exam');
    }
}
