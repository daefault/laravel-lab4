<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CharacterController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\AuthController;

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

// Публичные маршруты (без аутентификации)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->accessToken;
        
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
    
    return response()->json(['error' => 'Unauthorized'], 401);
});

// Защищенные маршруты (требуют токен)
Route::middleware('auth:api')->group(function () {
    // Персонажи (Character)
    Route::apiResource('characters', CharacterController::class);
    
    // Комментарии (Comment)
    Route::apiResource('comments', CommentController::class);
    
    // Профиль пользователя
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Создание персонального токена
    Route::post('/tokens/create', [AuthController::class, 'createToken']);
});