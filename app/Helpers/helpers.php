<?php
use App\Enums\RolesEnum;
use App\Models\Domain;
use Illuminate\Support\Str;
const USERS_PER_PAGE = 10;
const QUIZZES_PER_PAGE = 10;
const QUESTIONS_PER_PAGE = 10;
const CHOICES_PER_PAGE = 10;
if (!function_exists('isDashboardSuperadmin')) {
    function isDashboardSuperadmin()
    {
        $user = auth()->user();
        return $user && auth()->user()->hasRole(RolesEnum::SUPERADMIN);
    }
}
if (!function_exists('isDashboardAdmin')) {
    function isDashboardAdmin()
    {
        $user = auth()->user();
        if ($user && $user->tenant_id && $user->hasRole(RolesEnum::ADMIN)) {
            $url = url()->current();
            $domain = Domain::where('tenant_id', $user->tenant_id)->first();
            return $domain && Str::contains($url, $domain->domain);
        }
        return false;
    }
}
if (!function_exists('tenantId')) {
    function tenantId()
    {
        return tenant('id');
    }
}
