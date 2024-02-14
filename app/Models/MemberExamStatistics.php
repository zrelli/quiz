<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberExamStatistics extends Model
{
    use HasFactory;
    protected $casts = [
        'questions_data' => 'json',
    ];
    public function exam(): BelongsTo
    {
        return $this->belongsTo(MemberQuiz::class, 'member_quiz_id', "id");
    }
    public function scopeMaxScore($query)
    {
        return $query->orderBy('score', 'desc')->first();
    }
    public function setAnswerStatus($status): void
    {
        if ($this->current_question_index <= (count($this->questions_data) - 1)) {
            $questionsData = $this->questions_data;
            $questionsData[$this->current_question_index] = $status;
            $this->questions_data = $questionsData;
            $this->current_question_index++;
            $this->save();
            //
            if ($this->current_question_index == (count($this->questions_data))) {
                $this->calculateFinalStatistics();
            }
        }
    }
    public function isSuccessfulAttempt()
    {
        return $this->score >= 70;
    }
    public function calculateFinalStatistics(): void
    {
        //calculate score
        $finalResult = $this->questions_data;
        $sum = array_sum($finalResult);
        $totalElements = count($finalResult);
        $percentage = ($sum / $totalElements) * 100;
        $score =  intval($percentage);
        $createdAt = $this->created_at;
        // Calculate time taken
        $duration = $createdAt->diffInMinutes(now());
        $this->is_closed = true;
        $this->time_taken = $duration;
        $this->score = $score;
        $this->save();
        $currentExam = MemberQuiz::where('id', $this->member_quiz_id)->first();
        $currentMember = $currentExam->member;
        $bestTest = self::where('member_quiz_id', $currentExam->id)->maxScore();
        $isSuccessful = $bestTest->score >= 70;
        $currentExam->score = $bestTest->score;
        $currentExam->time_taken = $bestTest->time_taken;
        $currentExam->is_successful = $isSuccessful;
        $currentExam->save();
        if (is_null($currentMember->score)) {
            $currentMember->score = $bestTest->score;
        } else {
            $AverageScorePercentageCurrentMember = (($currentMember->score + $currentExam->score) / 2);
            $averageScoreCurrentMember =  intval($AverageScorePercentageCurrentMember, 0);
            $currentMember->score = $averageScoreCurrentMember;
        }
        $currentMember->total_attempts = $currentMember->total_attempts + $currentExam->total_attempts;
        $currentMember->time_taken = $currentMember->time_taken + $bestTest->time_taken;
        $isSuccessful ? $currentMember->successful_attempts++ : $currentMember->failed_attempts++;
        $currentMember->save();
    }
    public function isAnsweredAllQuestions(): bool
    {
        return ($this->current_question_index == count($this->questions_data));
    }
    public function currentAttemptTimeProgress()
    {
        return round(($this->created_at->diffInSeconds(now())) / ($this->exam->quiz->duration * 3600), 4) * 100;
    }
    public function leftTime()
    {
        $leftTime = ($this->exam->quiz->duration * 3600) -  $this->created_at->diffInSeconds(now());
        if ($leftTime < 0) {
            $leftTime = 0;
        }
        return $leftTime;
    }
}
