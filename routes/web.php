<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route untuk landing page AprilianaFast (Single Page / One Page Scrolling).
| Semua section (Home, About, Services, Price, Portfolio, Contact) berada
| dalam satu view: resources/views/index.blade.php
|
*/

Route::get('/', function () {
    return view('index');
})->name('home');