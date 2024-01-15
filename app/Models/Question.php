<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
// use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class Question extends Model
{
    // use BelongsToTenant; // It is a secondary model  (models that indirectly belongTo tenants)
    // We should apply BelongsToPrimaryModel trait for a secondary model
    use BelongsToPrimaryModel,HasFactory;
    // public function quiz(): BelongsTo
    // {
    //     return $this->belongsTo(Quiz::class);
    // }
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($question) {
            $question->slug = Str::slug($question->question);
        });
    }
    public function getRelationshipToPrimaryModel(): string
    {
        return 'quiz';
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }
}
