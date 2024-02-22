<?php
namespace App\Jobs;
use App\Mail\AdminNewMemberSubscribedToExamMail;
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
class NewMemberSubscribedToExamMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    protected $member;
    protected $quiz;
    protected $baseUrl;
    public $tries = 3;
    public $priority = 1;
    public function __construct($member, $quiz, $baseUrl)
    {
        $this->member = $member;
        $this->quiz = $quiz;
        $this->baseUrl = $baseUrl;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->member->email)->send(new AdminNewMemberSubscribedToExamMail($this->member->name, $this->baseUrl, $this->quiz));
    }
}
