<?php
namespace App\Observers;
use App\Models\Choice;
class ChoiceObserver
{
    /**
     * Handle the Choice "created" event.
     */
    public function created(Choice $choice): void
    {
        //
        $question = $choice->question;
        if (!$question->has_multiple_answers && $choice->is_correct) {
            $question->choices()
                ->where('id', '!=', $choice->id)
                ->update(['is_correct' => false]);
        }
    }
    /**
     * Handle the Choice "updated" event.
     */
    public function updated(Choice $choice): void
    {
        $question = $choice->question;
        if (!$question->has_multiple_answers && $choice->is_correct) {
            $question->choices()
                ->where('id', '!=', $choice->id)
                ->update(['is_correct' => false]);
        }
    }
    /**
     * Handle the Choice "deleted" event.
     */
    public function deleted(Choice $choice): void
    {
        //
    }
    /**
     * Handle the Choice "restored" event.
     */
    public function restored(Choice $choice): void
    {
        //
    }
    /**
     * Handle the Choice "force deleted" event.
     */
    public function forceDeleted(Choice $choice): void
    {
        //
    }
}
