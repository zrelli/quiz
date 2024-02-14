<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ExamQuestionCard extends Component
{


   public $question;
   
    public function render()
    {
        return view('livewire.components.exam-question-card');
    }
    
}
