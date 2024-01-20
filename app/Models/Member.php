<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Member  extends Authenticatable
{
    use BelongsToTenant, HasFactory;
    protected $guard = 'member';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
