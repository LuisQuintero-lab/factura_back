<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas para poder utilizar los metodos que creamos
Route::post('/send/{uid}', [InvoiceController::class, 'send'])->name('api.invoice.send');
Route::post('/cancel/{uid}', [InvoiceController::class, 'cancel'])->name('api.invoice.cancel');
Route::get('/show/{uid}', [InvoiceController::class, 'show'])->name('api.invoice.show');
Route::post('/store', [InvoiceController::class, 'store'])->name('api.invoice.store');
Route::post('/create', [InvoiceController::class, 'create'])->name('api.invoice.create');
Route::get('/index', [InvoiceController::class, 'index'])->name('api.invoice.index');




