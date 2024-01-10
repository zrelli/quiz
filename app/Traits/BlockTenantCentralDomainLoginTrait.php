<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait BlockTenantCentralDomainLoginTrait
{
    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function tenantTryToLoginFromMainDomain(Request $request)
    {
        if (!tenant('id')) {
            $email = $request->email;
            $superAdmin = User::where('id', 1)->first();
            //just for testing purpose
            //todo check if is a real admin by type
            //todo add many superadmins;
            if ($email != $superAdmin->email) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
        }
    }
}
