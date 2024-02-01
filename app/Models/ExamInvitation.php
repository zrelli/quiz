<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamInvitation extends Model
{
    use HasFactory;
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
