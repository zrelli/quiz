<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Quiz extends Model
{
    use BelongsToTenant,HasFactory;

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function periodOfTime(): HasOne

    {
        return $this->hasOne(QuizPeriodTime::class);
    }


    public function setStartDateAndDuration($startDate, $durationInHours)
    {
        $endDate = Carbon::parse($startDate)->addHours($durationInHours);

        if ($this->periodOfTime) {
            $this->periodOfTime->update([
                'start_time' => $startDate,
                'end_time' => $endDate,
            ]);
        } else {
            $this->periodOfTime()->create([
                'start_time' => $startDate,
                'end_time' => $endDate,
            ]);
        }}

}
