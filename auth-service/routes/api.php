<?php

use App\Http\Controllers\AuthController;
use App\Jobs\DeleteUserJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Junges\Kafka\Facades\Kafka;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:api'])->group(function () {
   Route::get('/check', [AuthController::class, 'check']);
   Route::delete('/delete', [AuthController::class, 'delete']);
});


// Route::get('/test', function () {

//    try {
//       $message = 'Hello, RabbitMQ!';
//       DeleteUserJob::dispatch($message);
//       return response()->json(['message' => 'Message sent!'], 200);
//    } catch (\Exception $e) {
//       return response()->json(['message' => $e->getMessage()], 500);
//    }
// });
