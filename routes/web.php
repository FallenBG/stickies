<?php

use Encore\Stickies\Http\Controllers\StickiesController;

Route::get('stickies', StickiesController::class.'@index');
Route::post('stickies/saveAll', StickiesController::class.'@saveAll');
Route::post('stickies/getAll', StickiesController::class.'@getAll');
Route::post('stickies/delete', StickiesController::class.'@delete');

//Route::post('stickies/saveAll/{stickies}', StickiesController::class.'@saveAll');
