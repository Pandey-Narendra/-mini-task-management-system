<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Custom API JSON response for API errors.
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'type' => class_basename($e),
            ], $this->getStatusCode($e));
        }

        return parent::render($request, $e);
    }

    private function getStatusCode(Throwable $e): int
    {
        return method_exists($e, 'getStatusCode')
            ? $e->getStatusCode()
            : 500;
    }
}
