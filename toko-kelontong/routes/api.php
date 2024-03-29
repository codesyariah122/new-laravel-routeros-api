<?php
namespace App\Http\Controllers\Api;
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
    
Route::prefix('v1')->group(function(){
    Route::post('/send-message', [TokoKelontongController::class, 'contact_message']);
    Route::post('/newsletter', [TokoKelontongController::class, 'newsletter']);
    Route::get('/location', [TokoKelontongController::class, 'location']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
