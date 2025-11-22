<?php

use App\Livewire\Auth\CustomLogin;
use App\Livewire\Home;
use App\Livewire\Post\Show as PostShow;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/fstarter/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/fstarter/livewire/livewire.js', $handle);
});

Route::get('/', function () {
    return redirect('/app');
})->name('home');

//Route::get('/app', CustomLogin::class)->name('login')->middleware('guest');

Route::get('/log', function (Request $request) {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/clr', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";

    $cleardebugbar = Artisan::call('route:clear');
    echo "Debug Bar cleared<br>";
});

Route::group(['middleware' => ['auth.app']], function () {
    Route::get('view-invoice/{id?}', [\App\Http\Controllers\AccountController::class, 'getInvoice'])->name('viewinvoice');
    Route::get('view-musak/{id?}', [\App\Http\Controllers\AccountController::class, 'getMusak'])->name('viewmusak');
    Route::get('view-cnf/{id?}', [\App\Http\Controllers\AccountController::class, 'getCnf'])->name('viewcnf');

    Route::get('view-isps/{id?}', [\App\Http\Controllers\AccountController::class, 'getIsps'])->name('viewisps');

    Route::get('view-dms/{id?}', [\App\Http\Controllers\AccountController::class, 'getDms'])->name('viewdms');
    Route::get('show-dms/{id?}', [\App\Http\Controllers\AccountController::class, 'showFileViewer'])->name('file.show');
});
