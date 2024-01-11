<?php

namespace App\Traits;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait MemberActionsTrait
{
    
    public function createNewTenantMember( $data)
    {
        if (tenant('id')) {
            Member::create($data);
           
        }
    }
}
