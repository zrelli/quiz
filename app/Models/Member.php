<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;


class Member  extends Model
{
   use BelongsToTenant , HasFactory;
   public function user()
   {
       return $this->belongsTo(User::class);
   }

}