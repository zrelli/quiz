<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class Quiz extends Model
{
    use BelongsToTenant,HasFactory;
    // public $timestamps = ['expired_at','started_at'];
    protected $fillable = [
        'title',
        'description',
        'max_attempts',
        'duration',
        'test_type',
        'started_at',
        'slug',
        'tenant_id',
        'expired_at',
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
        public function  calculateExpirationDate($period=1){
            if ($this->periodOfTime) {
                $this->expired_at =  $this->periodOfTime->end_time;
            } else { 
                $this->expired_at =  Carbon::parse($this->created_at)->addHours($period*24);
            }
            $this->save();
        }
}
