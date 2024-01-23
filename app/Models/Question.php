<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
// use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class Question extends Model
{
    use BelongsToPrimaryModel, HasFactory;
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
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }
}
