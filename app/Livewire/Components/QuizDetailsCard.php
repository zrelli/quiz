<?php

namespace App\Livewire\Components;

use App\Repositories\QuizRepository;
use Filament\Notifications\Notification;
use Livewire\Component;

class QuizDetailsCard extends Component
{
    public $quizRepo;
    public $quiz;
    public $submitBtnContent;
    public $isExamPage = false;
    public $memberSubscribed  = false;
    public function  mount()
    {
        $this->memberSubscribed = $this->quiz->memberSubscribed(auth()->user()->id);
        $this->submitBtnContent = $this->memberSubscribed ? 'Show Exam' : 'Subscribe';
        // if ($this->isExamPage) {
        //     $this->submitBtnContent = 'Begin Exam';
        // }
    }
    public function render()
    {
        return view('livewire.components.quiz-details-card');
    }
    public function submit()
    {
        if (!$this->memberSubscribed) {
            $this->subscribeToExam();
        }
        redirect(route('filament.member.resources.member-quizzes.exam-details', $this->quiz->slug));
    }
    private function subscribeToExam()
    {
        $quizRepo = new QuizRepository(app());
        $quizRepo->subscribeToQuiz($this->quiz, auth()->user()->id);
        Notification::make()->title("You have subscribed successfully")
            ->icon('heroicon-o-document-text')
            ->iconColor('success')
            ->send();
    }
    private function beginExam()
    {
        $quizRepo = new QuizRepository(app());
        $quizRepo->subscribeToQuiz($this->quiz, auth()->user()->id);
        Notification::make()->title("You have subscribed successfully")
            ->icon('heroicon-o-document-text')
            ->iconColor('success')
            ->send();
    }
}
