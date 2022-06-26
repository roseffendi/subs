<?php

use App\Http\Controllers\Api\V1\{
    FieldController,
    SubscriberController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// For this test, authentication and authorization wasn't implemented.

Route::prefix('v1')->group(function($router){

    // Fields routes
    $router->prefix('fields')->group(function($router){
        $router->get('/', [FieldController::class, 'index']);
        $router->post('/', [FieldController::class, 'store']);
        $router->put('/{field}', [FieldController::class, 'update']);
        $router->delete('/{field}', [FieldController::class, 'destroy']);
    });

    // Subscribers routes
    $router->prefix('subscribers')->group(function($router){
        $router->get('/', [SubscriberController::class, 'index']);
        $router->get('/{subscriber}', [SubscriberController::class, 'show']);
        $router->post('/', [SubscriberController::class, 'store']);
        $router->put('/{subscriber}', [SubscriberController::class, 'update']);
        $router->delete('/{subscriber}', [SubscriberController::class, 'destroy']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
