<?php

use App\Http\Controllers\Api\ScoreFingerprintController;
use Illuminate\Support\Facades\Route;

Route::prefix('fingerpint')->name('fingerpint.')->group(function(){
    Route::post('score', ScoreFingerprintController::class)->name('score');
});