<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Quiz extends Model
{
    use BelongsToTenant, HasFactory;
    protected $fillable = [
        'title',
        'description',
        'max_attempts',
        'duration',
        // 'is_published',
        'test_type',
        'started_at',
        'tenant_id',
        'expired_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_public' => 'boolean',
    ];


    protected static function boot()
    {
        parent::boot();
        static::saving(function ($quiz) {
            $quiz->slug = Str::slug($quiz->title);
        });
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function exams(): HasMany
    {
        return $this->hasMany(MemberQuiz::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(ExamInvitation::class);
    }
    public function isExpired()
    {
        if ($this->test_type == 'out_of_time') {
            $expiredAt = $this->expired_at;
        } else {
            $expiredAt = $this->started_at;
        }
        return Carbon::now()->greaterThan($expiredAt);
    }
    public function isAlreadyAssigned($memberId = null)
    {
        $memberId = auth()->user() && isMemberApiRoute() ?  auth()->user()->id : $memberId;
        return   $this->exams()->where('member_id',  $memberId)->exists();
    }
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_quizzes');
    }

    public function memberSubscribed($memberId)
    {
        return $this->exams()->where(['member_id' => $memberId, 'quiz_id' => $this->id])->exists();
    }

    public function timeProgressStep()
    {

        return   round((100 / ($this->duration * 3600)), 4);
    }


    public function timeLeftToStart()
    {


        $currentDateTime = Carbon::now();

        if ($currentDateTime->greaterThan(Carbon::parse($this->started_at))) {
            return '';
        }

        $timeLeftInMinutes = Carbon::parse($this->started_at)->clone()->diffInMinutes($currentDateTime);

        $hoursLeft = floor($timeLeftInMinutes / 60);
        $minutesLeft = $timeLeftInMinutes % 60;

        $timeLeft = '';
        if ($hoursLeft > 0) {
            $timeLeft .= "$hoursLeft " . ($hoursLeft == 1 ? 'hour' : 'hours');
            if ($minutesLeft > 0) {
                $timeLeft .= " and ";
            }
        }
        if ($minutesLeft > 0) {
            $timeLeft .= "$minutesLeft " . ($minutesLeft == 1 ? 'minute' : 'minutes');
        }


        return $timeLeft;
        // return   round((100 / ($this->duration * 3600)), 4);
    }

    public function timeLeftToStartProgress()
    {
        // Assuming $exam->started_at is the start date and time of the exam
        $examStartTime = Carbon::parse($this->started_at);
        $createdAt = Carbon::parse($this->created_at);

        // Get the current date and time
        $currentDateTime = Carbon::now();

        // Calculate the total time until the exam starts
        $totalTime = $examStartTime->diffInSeconds($createdAt);

        // Calculate the time elapsed as a percentage of the total time
        $elapsedTime = $currentDateTime->diffInSeconds($createdAt);
        $progressPercentage = ($elapsedTime / $totalTime) * 100;

        // Format the progress percentage to two decimal places
        $progressPercentage = number_format($progressPercentage, 2);

        // dd($progressPercentage);
        return $progressPercentage;
    }
}
