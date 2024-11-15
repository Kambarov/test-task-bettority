<?php

use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

Route::prefix('rewards')->group(function (){
    Route::get('/',[RewardController::class,'index']);
    Route::post('/', [RewardController::class,'create']);
    Route::put('{reward}/update', [RewardController::class,'update']);
    Route::post('{reward}/{user_id}/attach', [RewardController::class,'attachToUser']);
    Route::get('user/{user_id}', [RewardController::class,'getByUserId']);
    Route::delete('{reward}/delete', [RewardController::class,'delete']);
});
