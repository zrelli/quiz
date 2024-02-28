<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueCompanyEmail implements Rule
{
    public function passes($attribute, $value)
    {
        $existsInUsers = DB::table('users')->where('email', $value)->exists();
        $existsInRequestCompanyAccounts = DB::table('request_company_accounts')->where('email', $value)->exists();

        return !$existsInUsers && !$existsInRequestCompanyAccounts;
    }

    public function message()
    {
        return 'The email address has already been taken.';
    }
}
