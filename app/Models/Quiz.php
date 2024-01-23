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
    public function exams(): HasMany
    {
        return $this->hasMany(MemberQuiz::class);
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
      public function isAlreadyAssigned($memberId=null)
      {
          $memberId = auth()->user() && isMemberApiRoute() ?  auth()->user()->id : $memberId;
       return   $this->exams()->where('member_id',  $memberId)->exists();
      }
}
