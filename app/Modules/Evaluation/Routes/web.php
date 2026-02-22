<?php

use App\Modules\Evaluation\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('evaluation')->name('evaluation.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
    });
});
