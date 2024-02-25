<?php

namespace App\Livewire;

use App\Models\Choice;
use App\Models\MemberQuiz;
use Filament\Notifications\Notification;
use Livewire\Component;

class ExamDetails extends Component
{
    public $quizRepo;
    public $quiz;
    public $examHasStarted = false;
    public $questionNumber = 0;
    public $duration = 0;
    public $questions = [];
    public $currentQuestion;
    public $choices = [];
    public $totalQuestions = 0;
    public $examFinished = false;
    public $selectedChoice;
    public $step;
    public $passedTimeProgress = 0;
    public $lastExamAttempt;
    public $attemptExpired = false;
    public $currentExam;
    public $leftAttempts = 0;
    public $leftTime = 0;
    public $startExamBtnContent = 'Begin Exam';
    public $resumeExam = false;
    public $timeLeftToStart;
    public $timeLeftToStartProgress;
    public function mount()
    {
        $this->initData();
        $this->setExamBtnContent();
        $this->setupQuestionFormData(true);
        $this->timeLeftToStart = $this->quiz->timeLeftToStart();
        $this->timeLeftToStartProgress = $this->quiz->timeLeftToStartProgress();
    }
    private function initData()
    {
        $this->questions = $this->quiz->questions->all();
        $this->totalQuestions = count($this->questions);
        $this->step = $this->quiz->timeProgressStep();
        $this->currentExam = MemberQuiz::where(['member_id' => auth()->user()->id, 'quiz_id' => $this->quiz->id])->first();
        $this->leftAttempts = $this->currentExam?->leftAttempts();
        $this->lastExamAttempt = $this->currentExam?->lastExamAttempt();
        $this->leftTime = $this->lastExamAttempt?->leftTime() ?? 0;
        $this->passedTimeProgress = $this->lastExamAttempt?->currentAttemptTimeProgress() ?? 0;
        $this->validateExamStatus();
    }
    private function validateExamStatus()
    {
        $showErrorNotification = $this->lastExamAttempt && $this->leftTime <= 0 && !$this->lastExamAttempt->is_closed;
        if ($showErrorNotification) {
            $this->attemptExpired = true;
            Notification::make()->title('This exam attempt has been expired')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->send();
        }
    }
    public function retryExam()
    {
        redirect()->route('filament.member.resources.member-quizzes.view', $this->quiz->slug);
        $this->dispatch('reload:exam-results');
    }
    private function setExamBtnContent()
    {
        if ($this->lastExamAttempt && $this->lastExamAttempt->is_closed) {
            $this->startExamBtnContent = "Retry Exam";
        }
        if ($this->currentExam?->alreadyHasPendingExam()) {
            if ($this->attemptExpired) {
                $this->startExamBtnContent = "Show Result";
            } else {
                $this->startExamBtnContent = "Resume Exam";
                $this->resumeExam = true;
            }
        }
    }
    public function render()
    {
        $this->timeLeftToStart = $this->quiz->timeLeftToStart();
        $this->timeLeftToStartProgress = $this->quiz->timeLeftToStartProgress();
        return view('livewire.exam-details')->with(['timeLeftToStartProgress' => $this->timeLeftToStartProgress, 'timeLeftToStart' => $this->timeLeftToStart]);
    }
    private function canBeginExamValidation()
    {
        if (!$this->currentExam->alreadyHasPendingExam()) {
            $errors = $this->currentExam->canTakeExam();
            if (count($errors)) {
                Notification::make()->title($errors[0])
                    ->icon('heroicon-o-document-text')
                    ->iconColor('danger')
                    ->send();
                return;
            }
        }
    }
    public function beginExam()
    {
        $this->canBeginExamValidation();
        if ($this->currentExam->isNewExamTest()) {
            $this->currentExam->startExam();
        }
        $this->initExamData();
    }
    private function initExamData()
    {
        $this->lastExamAttempt = $this->currentExam->lastExamAttempt();
        $this->passedTimeProgress = $this->lastExamAttempt->currentAttemptTimeProgress();
        $this->leftTime = $this->lastExamAttempt->leftTime();
        $this->dispatch('examProgressUpdated', progress: $this->passedTimeProgress, leftTime: $this->leftTime);
        $this->examHasStarted = true;
        if ($this->attemptExpired) {
            $this->calculateExamResult();
        }
    }
    private function calculateExamResult()
    {
        $examTest = $this->currentExam->lastExamAttempt();
        $examTest->calculateFinalStatistics();
        $this->completeExam();
    }
    public function nextQuestion()
    {
        if (!$this->selectedChoice || $this->leftTime <= 0) {
            if ($this->attemptExpired) {
                $this->calculateExamResult();
            }
            return;
        }
        if (($this->questionNumber <= $this->totalQuestions)) {
            $this->answerQuestion();
            if ($this->questionNumber != $this->totalQuestions) {
                $this->setupQuestionFormData();
            } else {
                $this->completeExam();
            }
        }
    }
    private function completeExam()
    {
        $this->lastExamAttempt = $this->currentExam->lastExamAttempt();
        $this->examFinished = true;
        $this->dispatch('examCompleted');
    }
    private function setupQuestionFormData($firstReload = false)
    {
        $this->questionNumber++;
        if ($this->lastExamAttempt && !$this->lastExamAttempt->is_closed && $firstReload) {
            $this->questionNumber =  $this->lastExamAttempt->current_question_index + 1;
        }
        $this->currentQuestion = $this->questions[($this->questionNumber - 1)];
        $this->choices = $this->currentQuestion->choices->all();
        $this->selectedChoice = null;
    }
    public function answerQuestion()
    {
        $choice = Choice::where('id', '=', $this->selectedChoice)->first();
        $examTest = $this->currentExam->lastExamAttempt();
        $status = $choice->is_correct ? 1 : 0;
        $examTest->setAnswerStatus($status);
    }
}
