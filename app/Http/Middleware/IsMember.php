<?php
namespace App\Http\Middleware;
use App\Enums\RolesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
// 
if (auth()->user() && auth()->user()->hasRole(RolesEnum::MEMBER)) {
    return $next($request);
}
// Redirect or return an unauthorized response
return response()->json(['message' => 'Unauthorized.','success'=>false], 401);
        return $next($request);
    }
}
