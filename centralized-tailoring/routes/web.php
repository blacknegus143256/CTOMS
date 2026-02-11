<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\ViewShop;

// Point directly to the blade file name ('home-page')
Route::get('/', HomePage::class)->name('home');
Route::get('/shop/{shop}', ViewShop::class)->name('shop.show');
