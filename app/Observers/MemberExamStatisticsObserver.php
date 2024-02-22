<?php

namespace App\Observers;

use App\Jobs\MemberExamCompletedMail;
use App\Jobs\NewMemberSubscribedToExamMail;
use App\Models\MemberExamStatistics;

class MemberExamStatisticsObserver
{
    /**
     * Handle the MemberExamStatistics "created" event.
     */
    public function created(MemberExamStatistics $memberExamStatistics): void
    {
        initTenant();
        $quiz = $memberExamStatistics->quiz;
        $member = auth()->user();
        $baseUrl = route('filament.company.resources.quizzes.view', $quiz->id) . '?activeRelationManager=1';
        dispatch(new NewMemberSubscribedToExamMail($member, $quiz, $baseUrl));
    }
    /**
     * Handle the MemberExamStatistics "updated" event.
     */
    public function updated(MemberExamStatistics $memberExamStatistics): void
    {
        $originalValue = $memberExamStatistics->getOriginal('is_closed');
        $updatedValue = $memberExamStatistics->is_closed;
        if ($originalValue !=  $updatedValue) {
            initTenant();
            $quiz = $memberExamStatistics->quiz;
            $member = auth()->user();
            $baseUrl = route('filament.company.resources.quizzes.view', $quiz->id) . '?activeRelationManager=1';
            dispatch(new MemberExamCompletedMail($member, $quiz, $baseUrl));
        }
    }
    /**
     * Handle the MemberExamStatistics "deleted" event.
     */
    public function deleted(MemberExamStatistics $memberExamStatistics): void
    {
    }
    /**
     * Handle the MemberExamStatistics "restored" event.
     */
    public function restored(MemberExamStatistics $memberExamStatistics): void
    {
        //
    }
    /**
     * Handle the MemberExamStatistics "force deleted" event.
     */
    public function forceDeleted(MemberExamStatistics $memberExamStatistics): void
    {
        //
    }
}
