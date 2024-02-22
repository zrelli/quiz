<?php
namespace App\Livewire\Pages;
use App\Models\ExamInvitation;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Livewire\Component;
class MembereExamInvitation extends Component
{
    public $invitationCode;
    public ExamInvitation $invitation;
    public Quiz $quiz;
    public $message;
    public $type = 'success';
    public function mount($code)
    {
        $this->getQuiz($code);
    }
    public function render()
    {
        $quiz = $this->quiz;
        return view('livewire.pages.membere-exam-invitation')
            ->with(['quiz' => $quiz, 'invitation' => $this->invitation])
            ->layout('layouts.guest');
    }
    public function getQuiz($code)
    {
        $parts = explode('___', $code);
        list($token, $id) = $parts;
        $this->invitation = ExamInvitation::findOrFail($id);
        $this->quiz = $this->invitation->quiz;
        if ($token != $this->invitation->token) {
            $this->message = "This invitation not valid";
            $this->type = 'error';
            return;
        }
        if ($this->invitation->is_accepted == null) {
            return;
        }
        if ($this->invitation->is_accepted == true) {
            $this->redirectToExamPage();
        }
        if ($this->invitation->is_accepted == false) {
            $this->message = "You have declined this invitation";
            $this->type = 'error';
        }
    }
    public function acceptInvitation()
    {
        $this->invitation->is_accepted = true;
        $this->invitation->save();
        $data = ['member_id' => $this->invitation->member_id, 'quiz_id' => $this->invitation->quiz_id];
        MemberQuiz::create($data);
        $this->redirectToExamPage();
    }
    public function declineInvitation()
    {
        $this->invitation->is_accepted = false;
        $this->invitation->save();
        $this->message = "You have declined this invitation";
        $this->type = 'error';
    }
    public function redirectToExamPage()
    {
        $slug = $this->quiz->slug;
        redirect(route('filament.member.resources.member-quizzes.view', $slug));
    }
}
