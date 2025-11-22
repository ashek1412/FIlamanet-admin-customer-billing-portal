<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/upload-pdf', [\App\Http\Controllers\AccountController::class,'uploadPdf'])->name('uploadinvPdf');
