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
    Route::get('/test-api', [RouterosController::class, 'test_api']);

    Route::get('/routeros-connect', [RouterosController::class, 'routeros_connection']);

    Route::get('/set-interface', [RouterosController::class, 'set_interface']);

    Route::get('/add-new-address', [RouterosController::class, 'add_new_address']);

    Route::get('/add-ip-route', [RouterosController::class, 'add_ip_route']);

    Route::get('/add-dns-server', [RouterosController::class, 'add_dns_servers']);

    Route::get('/masquerade-srcnat', [RouterosController::class, 'masquerade_srcnat']);

    Route::get('/routeros-reboot', [RouterosController::class, 'routeros_reboot']);

    Route::get('/routeros-shutdown', [RouterosController::class, 'routeros_shutdown']);
});