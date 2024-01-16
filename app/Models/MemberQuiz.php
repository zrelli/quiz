<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberQuiz extends Model
{
    use HasFactory;
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    //   
}
