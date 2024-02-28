<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueCompanyTenant implements Rule
{
    public function passes($attribute, $value)
    {
        $existsInUsers = DB::table('tenants')->where('id', $value)->exists();
        $existsInRequestCompanyAccounts = DB::table('request_company_accounts')->where('id', $value)->exists();

        return !$existsInUsers && !$existsInRequestCompanyAccounts;
    }

    public function message()
    {
        return 'The Subdomain address has already been taken.';
    }
}
