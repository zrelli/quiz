<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Member  extends Authenticatable
{
    use BelongsToTenant, HasFactory, HasApiTokens;
    protected $guard = 'member';
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // static::addGlobalScope(new TenantUserScope);
        // parent::boot();
        // static::saving(function ($member) {
        //     $member->password =   Hash::make($member->password);
        // });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(MemberQuiz::class);
    } public function invitations(): HasMany
    {
        return $this->hasMany(ExamInvitation::class);
    }
}
