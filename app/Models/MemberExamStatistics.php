<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MemberExamStatistics extends Model
{
    use HasFactory;
    protected $casts = [
        'questions_data' => 'json',
    ];
    public function exam()
    {
        return $this->belongsTo(MemberQuiz::class);
    }
    public function setResultStatus( $status)
    {
        // 0 or 1 
        if ($this->current_question_index <= (count($this->questions_data) - 1)) {
            $oldResult = $this->questions_data;
            $oldResult[$this->current_question_index] = $status;
            $this->questions_data = $oldResult;
            $this->current_question_index++;
            $this->save();
        } 
        // else {
        //     $this->save();
        //     $this->calculateFinalStatistics();
        // }
    }
    public function calculateFinalStatistics()
    {
        //calculate score
        $finalResult = $this->questions_data;
        $sum = array_sum($finalResult);
        $totalElements = count($finalResult);
        $percentage = ($sum / $totalElements) * 100;
        $score =  number_format($percentage);
        // $endedAt = now();
        // $this->ended_at = $endedAt;
        $createdAt = $this->created_at;
        // $endedAt = $this->ended_at;
        // Calculate time taken
        $duration = $createdAt->diffInMinutes(now());
        $this->is_closed = true;
        $this->time_taken = $duration;
        $this->average_score = $score;
        $this->save();
    }
}
