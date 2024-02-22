<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberQuiz extends Model
{
    use HasFactory;
    protected $appends = ['slug'];

    // The 'data' attribute will be automatically cast to/from JSON
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
        // return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
    }

    public function getSlugAttribute()
    {
        return $this->quiz->slug;
    }    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
    public function examStatistics(): HasMany
    {
        return $this->hasMany(MemberExamStatistics::class);
    }
    public function lastExamAttempt()
    {
        return $this->examStatistics()->latest('created_at')->first();
    }
    public function startExam()
    {
        // todo => check exam attempts and last test is closed or no
        // we will use a scheduled job to close it after time expired
        // and also check if the exam is closed or not
        $this->total_attempts++;
        $this->save();
        $data =  array_fill(0, $this->quiz->questions()->count(), 0);
        return $this->examStatistics()->create(['questions_data' => $data]);
    }
    public function canTakeExam()
    {
        $errors = [];
        $quiz = $this->quiz;
        $lastExamAttempt = $this->lastExamAttempt();
        $AttemptsCompleted = $this->total_attempts == $quiz->max_attempts;
        $isExpired = $quiz->isExpired();
        $lastExamStatisticsCompleted = $lastExamAttempt ? !$lastExamAttempt->is_closed : false;
        if ($AttemptsCompleted) {
            $errors[] = 'There is no other attempt';
        }
        if ($isExpired) {
            $errors[] = 'Exam date has been expired';
        }
        if ($lastExamStatisticsCompleted) {
            $errors[] = 'Already has a progress exam test';
        }
        return $errors;
    }
    public function scopeFindByMemberAndQuiz($query, $memberId, $quizId)
    {
        return $query->where(['member_id' => $memberId, 'quiz_id' => $quizId]);
    }
    public function questions()
    {
        return $this->quiz->questions();
    }
    public function alreadyHasPendingExam()
    {
        $lastExamAttempt =  $this->lastExamAttempt();
        $isPending = false;
        if ($lastExamAttempt) {
            $isPending = !$lastExamAttempt->is_closed;
        }

        // dd($lastExamAttempt);
        return $isPending;
        // &&  !$this->lastExamAttempt()->is_closed;
        // if(!$this->lastExamAttempt||$this->lastExamAttempt->is_closed
        // ){
        //     return false;
        // }
        // // $now = now();
        // $startedAt = $this->lastExamAttempt->created_at;
        // $expiredAt = $startedAt->addHours($this->quiz->duration);
        // // $data= Carbon::now()->greaterThan($expiredAt);
        // $data= Carbon::now()->lessThan($expiredAt);
        // dd($data);
        // return;
        // $data = $this->currentExam->lastExamAttempt->where('created_at', '>', $twoHoursLater)->where('is_closed', false);
        // dd($data);
    }
    public function  isNewExamTest()
    {
        $lastExamAttempt =  $this->lastExamAttempt();
        return  !$lastExamAttempt ||  ($lastExamAttempt &&  $lastExamAttempt->is_closed);
    }
    public function leftAttempts()
    {

        return ($this->quiz->max_attempts - $this->examStatistics->count());
    }
}
