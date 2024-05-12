<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Contact;


Route::get('contact', Contact::class)->name('contact')->middleware('auth');
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
