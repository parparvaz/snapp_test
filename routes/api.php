<?php

use App\Http\Controllers\SwapController;
use App\Http\Controllers\TransactionController;

Route::post('swap', [SwapController::class, 'swap'])->name('swap');

Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');
