<?php

namespace App\Observers;

use App\Jobs\MemberExamCompletedMail;
use App\Jobs\NewMemberSubscribedToExamMail;
use App\Models\Quiz;
use Carbon\Carbon;

class QuizObserver
{
    /**
     * Handle the Quiz "created" event.
     */
    public function created(Quiz $quiz): void
    {
    }
    /**
     * Handle the Quiz "updated" event.
     */
    public function updating(Quiz $quiz): void
    {


        if ($quiz->isDirty(['duration', 'test_type'])) {
            $timeDuration = $quiz['test_type'] == 'out_of_time'
                ? ($quiz['duration'] * 24)
                : $quiz['duration'];
            $quiz->expired_at =  Carbon::createFromFormat('Y-m-d H:i:s', $quiz['started_at'])
                ->addHours($timeDuration)->format('Y-m-d H:i:s');
        }
    }
    /**
     * Handle the Quiz "deleted" event.
     */
    public function deleted(Quiz $quiz): void
    {
    }
    /**
     * Handle the Quiz "restored" event.
     */
    public function restored(Quiz $quiz): void
    {
        //
    }
    /**
     * Handle the Quiz "force deleted" event.
     */
    public function forceDeleted(Quiz $quiz): void
    {
        //
    }
}
