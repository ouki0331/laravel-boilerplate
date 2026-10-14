<?php

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Standardized RESTful API endpoints.
| All responses use App\Helpers\ApiResponse format.
|
*/

Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/ping', function () {
        return ApiResponse::success([
            'environment' => config('app.env'),
            'timestamp' => now()->toIso8601String(),
        ], 'Pong');
    });

    // Authenticated user
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return ApiResponse::success($request->user());
    });
});
