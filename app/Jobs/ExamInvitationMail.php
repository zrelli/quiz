<?php

namespace App\Jobs;

use App\Mail\MemberSubscribedToPrivateExamMail;
use App\Models\ExamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExamInvitationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    protected $members;
    protected $quiz;
    protected $baseUrl;
    public $tries = 3;
    public $priority = 1;
    public function __construct($members, $quiz, $baseUrl)
    {
        $this->members = $members;
        $this->quiz = $quiz;
        $this->baseUrl = $baseUrl;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {


        foreach ($this->members as $member) {
            $this->sendMail($member);
        }
    }
    private function sendMail($member)
    {
        try {
            $memberQuizData = [
                'member_id' => $member->id,
                'quiz_id' => $this->quiz->id,
            ];
            $invitation = ExamInvitation::firstOrCreate($memberQuizData, ['token' => Str::random(60)]);
            if ($invitation->wasRecentlyCreated) {
                $urlToken = 'http://' . $this->baseUrl . '/members/exam-invitation/' . $invitation->token . '___' . $invitation->id;
                Mail::to($member->email)->send(new MemberSubscribedToPrivateExamMail($member->name, $urlToken, $this->quiz));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
