<?php

namespace App\Jobs;

use App\Mail\CompanyAccountRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CompanyAccountRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    protected $users;
    public $tries = 3;
    public $priority = 1;
    public function __construct($users)
    {
        $this->users = $users;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dashboardUrl = route('filament.company.pages.dashboard');

        foreach ($this->users as $user) {
            $tenantUrl = preg_replace('/(https?:\/\/)([^\/]+)(\/.*)/', '$1' . $user->tenant_id . '.$2$3', $dashboardUrl);



            Mail::to($user->email)->send(new CompanyAccountRequestMail($user->name, $tenantUrl));
        }
    }
}
