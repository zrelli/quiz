<?php

namespace App\Providers;

use App\Events\SendCompanyAccountRequestMailsEvent;
use App\Events\SendExamInvitationMailsEvent;
use App\Events\SendExamResultMailEvent;
use App\Events\SendMemberExamReminderMailsEvent;
use App\Listeners\SendMailListener;
use App\Models\Choice;
use App\Models\MemberExamStatistics;
use App\Models\Quiz;
use App\Observers\ChoiceObserver;
use App\Observers\MemberExamStatisticsObserver;
use App\Observers\QuizObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendExamInvitationMailsEvent::class => [
            SendMailListener::class,
        ],
        SendMemberExamReminderMailsEvent::class => [
            SendMailListener::class,
        ],
        SendExamResultMailEvent::class => [
            SendMailListener::class,
        ],

        SendCompanyAccountRequestMailsEvent::class => [
            SendMailListener::class,
        ],


    ];
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        MemberExamStatistics::observe(MemberExamStatisticsObserver::class);
        Quiz::observe(QuizObserver::class);
        Choice::observe(ChoiceObserver::class);
    }
    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
