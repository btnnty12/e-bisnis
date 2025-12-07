<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/mood/senang', function () {
    return view('senang');
})->name('mood.senang');

Route::get('/mood/sedih', function () {
    return view('sedih');
})->name('mood.sedih');

Route::get('/mood/stress', function () {
    return view('stress');
})->name('mood.stress');

Route::get('/mood/lelah', function () {
    return view('lelah');
})->name('mood.lelah');

Route::get('/mood/biasa-aja', function () {
    return view('biasa-aja');
})->name('mood.biasa-aja');

Route::get('/mood/excited', function () {
    return view('excited');
})->name('mood.excited');
