<?php
namespace App\Events;
use App\Jobs\ExamInvitationMail;
use App\Models\Member;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class SendExamInvitationMailsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $membersId;
    protected $quiz;
    protected $baseUrl;
    /**
     * Create a new event instance.
     */
    public function __construct($membersId, $quiz, $baseUrl)
    {
        $this->membersId = $membersId;
        $this->quiz = $quiz;
        $this->baseUrl = $baseUrl;
    }
    public function handle()
    {
        $chunkSize = 100;
        Member::select('id', 'name', 'email')
            ->when($this->membersId, function ($query) {
                return $query->whereIn('id', $this->membersId);
            })
            ->chunk($chunkSize, function ($members) {
                dispatch(new ExamInvitationMail($members, $this->quiz, $this->baseUrl));
            });
    }
}
