<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class ExamResults extends Component
{
    public $allExamStatistics = [];
    public $exam;
    public function mount()
    {
        $this->getExamStatistics();
    }
    public function render()
    {
        return view('livewire.components.exam-results');
    }
    #[On('reload:exam-results')]
    private function   getExamStatistics()
    {
        $this->allExamStatistics = $this->exam->examStatistics;
    }
}
