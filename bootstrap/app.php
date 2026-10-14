<?php

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Standardize API / JSON responses
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return ApiResponse::error(
                        'Validation failed',
                        422,
                        $e->errors()
                    );
                }

                if ($e instanceof AuthenticationException) {
                    return ApiResponse::error(
                        'Unauthenticated',
                        401
                    );
                }

                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return ApiResponse::error(
                        'Resource not found',
                        404
                    );
                }

                if ($e instanceof HttpException) {
                    return ApiResponse::error(
                        $e->getMessage() ?: 'HTTP Error',
                        $e->getStatusCode()
                    );
                }

                return ApiResponse::error(
                    config('app.debug') ? $e->getMessage() : 'Internal Server Error',
                    500,
                    config('app.debug') ? ['trace' => $e->getTraceAsString()] : null
                );
            }

            return null;
        });
    })->create();
