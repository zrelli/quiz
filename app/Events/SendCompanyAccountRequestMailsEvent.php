<?php
namespace App\Events;
use App\Jobs\ExamInvitationMail;
use App\Jobs\CompanyAccountRequest;
use App\Models\Member;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class SendCompanyAccountRequestMailsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $emails;//member mails
    /**
     * Create a new event instance.
     */
    public function __construct($emails)
    {
        $this->emails = $emails;
    }
    public function handle()
    {
        $chunkSize = 100;
        User::select('id', 'name', 'email')->whereIn('email', $this->emails)

            ->chunk($chunkSize, function ($users) {
                dispatch(new CompanyAccountRequest($users));



            });
    }
}
