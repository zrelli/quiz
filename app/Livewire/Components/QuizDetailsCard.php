<?php

namespace App\Livewire\Components;

use App\Repositories\QuizRepository;
use Filament\Notifications\Notification;
use Livewire\Component;

class QuizDetailsCard extends Component
{
    public $quiz;
    public $submitBtnContent;
    public $isExamPage = false;
    public $memberSubscribed  = false;
    public $isResourcePage;
    public $canShowFooterBtn = false;
    public function  mount()
    {
        $this->memberSubscribed = auth()->user() ? $this->quiz->memberSubscribed(auth()->user()->id) : [];
        $this->submitBtnContent = $this->memberSubscribed ? 'Show Exam' : 'Subscribe';
    }
    public function render()
    {
        $this->showFooterBtn();
        return view('livewire.components.quiz-details-card');
    }
    public function submit()
    {
        if (!$this->memberSubscribed) {
            $this->subscribeToExam();
        }
        redirect(route('filament.member.resources.member-quizzes.view', $this->quiz->slug));
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
    private function  showFooterBtn()
    {
        $this->canShowFooterBtn =  (!$this->isExamPage  && $this->isResourcePage && !$this->quiz->isExpired())
            || ($this->submitBtnContent == 'Show Exam' && $this->isResourcePage);
    }
}
