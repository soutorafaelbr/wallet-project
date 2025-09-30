<?php

use App\Http\Controllers\Transference\MakeTransference;
use Illuminate\Support\Facades\Route;

Route::post('transfer', MakeTransference::class)->name('transference');
