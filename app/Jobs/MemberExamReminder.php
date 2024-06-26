<?php
namespace App\Jobs;
use App\Mail\MemberExamReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class MemberExamReminder implements ShouldQueue
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
            Mail::to($member->email)->send(new MemberExamReminderMail($member->name, $this->baseUrl, $this->quiz));
        }
    }
}
