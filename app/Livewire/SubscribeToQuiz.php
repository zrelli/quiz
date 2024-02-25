<?php

namespace App\Livewire;

use App\Repositories\QuizRepository;
use Filament\Notifications\Notification;
use Livewire\Component;

class SubscribeToQuiz extends Component
{
    public $quizRepo;
    public $quiz;
    public function render()
    {
        return view('livewire.subscribe-to-quiz', ['quiz' => $this->quiz]);
    }
    public function submit()
    {
        $quizRepo = new QuizRepository(app());
        $quizRepo->subscribeToQuiz($this->quiz, auth()->user()->id);
        Notification::make()->title("You have subscribed successfully")
            ->icon('heroicon-o-document-text')
            ->iconColor('success')
            ->send();
        redirect(route('filament.member.resources.quizzes.index'));
    }
}
