<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class AddApplicationJsonHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->path();
        $pattern = '/^api\/v(\d+)/';
        if (preg_match($pattern, $route, $matches)) {
            $request->headers->set('Accept', 'application/json');
       }
        return $next($request);
    }
}
