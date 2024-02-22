<?php
namespace App\Jobs;

use App\Mail\MemberExamResultMail;
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
class ExamResultMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
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

            Mail::to($member->email)->send(new MemberExamResultMail($member->name, $this->baseUrl, $this->quiz));



        }

    }






}
