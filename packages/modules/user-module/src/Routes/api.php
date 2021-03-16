<?php

Route::get('email/exists', 'ValidateEmailController@checkEmailIfExists');
Route::get('/stats', 'UserStatsController@index');

Route::get('/all', 'UserController@index');
Route::get('/show', 'UserController@show');
Route::post('/update', 'UserController@update');
Route::post('/change-password', 'ChangePasswordController@update');
// Route::post('/delete/{id}', 'UserController@delete');
