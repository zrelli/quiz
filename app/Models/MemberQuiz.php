<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class MemberQuiz extends Model
{
    use HasFactory;
    // The 'data' attribute will be automatically cast to/from JSON
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function examStatistics(): HasMany
    {
        return $this->hasMany(MemberExamStatistics::class);
    }
    public function startExam()
    {
        // todo => check exam attempts and last test is closed or no
        // we will use a scheduled job to close it after time expired
        // and also check if the exam is closed or not 
        $data =  array_fill(0, $this->quiz->questions()->count(), 0);
        return $this->examStatistics()->create(['questions_data' => $data]);
    }
    public function completeExam()
    {
    }
    public function canTakeExamAgain()
    {
    }
    public function reTakeExam()
    {
    }
    public function calculateResult()
    {
    }
    //   
}
