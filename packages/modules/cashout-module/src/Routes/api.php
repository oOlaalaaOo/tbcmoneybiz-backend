<?php
Route::post('/submit', 'SubmitCashoutController@cashout');
Route::get('/all', 'CashoutController@getAll');
Route::post('/confirm', 'ConfirmCashoutController@confirm');
Route::post('/deny', 'DenyCashoutController@deny');
Route::get('/user/already-cashout', 'CashoutController@userAlreadyCashout');
