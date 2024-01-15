<?php
namespace App\Exceptions;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found',
                    'success' => false
                ], 404);
            } else if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'success' => false
                ], 401);
            }
        }
        return parent::render($request, $exception);
    }
}
