<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Rutas para poder utilizar los metodos que creamos
Route::post('/invoice/send/{uid}', [InvoiceController::class, 'send'])->name('invoice.send');
Route::post('/invoice/cancel/{uid}', [InvoiceController::class, 'cancel'])->name('invoice.cancel');
Route::get('/invoice/show/{uid}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::post('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::get('/invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice/clients', [InvoiceController::class, 'clients'])->name('invoice.clients');
Route::get('/invoice/cfdiUse', [InvoiceController::class, 'cfdiUse'])->name('invoice.cfdiUse');
Route::get('/invoice/series', [InvoiceController::class, 'series'])->name('invoice.series');
Route::get('/invoice/payWay', [InvoiceController::class, 'payWay'])->name('invoice.payWay');

