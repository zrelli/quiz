<?php
use App\Enums\RolesEnum;
use App\Models\Domain;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
const USERS_PER_PAGE = 10;
const MEMBER_PER_PAGE = 10;
const QUIZZES_PER_PAGE = 10;
const QUESTIONS_PER_PAGE = 10;
const CHOICES_PER_PAGE = 10;
const MEMBER_QUIZZES_PER_PAGE = 10;
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
if (!function_exists('isMyClient')) {
    function isMyClient($memberId)
    {
        $member =  Member::where(['id' => $memberId, 'tenant_id' => tenantId()])->first();
        return $member;
    }
}
if (!function_exists('isAdminApiRoute')) {
    function isAdminApiRoute()
    {
        $route = request()->path();
        $pattern = '/^api\/v(\d+)\/admin/';
        return (preg_match($pattern, $route, $matches));
    }
}
if (!function_exists('isMemberApiRoute')) {
    function isMemberApiRoute()
    {
        $route = request()->path();
        $pattern = '/^api\/v(\d+)\/member/';
        return (preg_match($pattern, $route, $matches));
    }
}
if (!function_exists('canLogin')) {
    function canLogin($user)
    {
        return (isAdminApiRoute()   &&  $user->hasRole(RolesEnum::ADMIN))
            ||
            (isMemberApiRoute()   &&  $user->hasRole(RolesEnum::MEMBER));
    }
}
if (!function_exists('currentAuthApiGuard')) {
    function currentAuthApiGuard()
    {
        $authGuard = isAdminApiRoute() ? Auth::guard('web') : Auth::guard('member');
        return $authGuard;
    }
}
if (!function_exists('authModel')) {
    function authModel()
    {
        $model = isAdminApiRoute() ? User::class : Member::class;
        return $model;
    }
}
