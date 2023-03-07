<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participant\Payment\PayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/stripe/webhook', [PayController::class, 'webhook'])->name('webhook');

require __DIR__.'/super_admin.php';
require __DIR__.'/admin.php';
require __DIR__.'/reviewer.php';
require __DIR__.'/participant.php';
