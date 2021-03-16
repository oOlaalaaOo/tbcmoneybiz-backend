<?php

Route::get('referral-link/exists', 'ValidateReferrerController@checkReferrerIdExists');
Route::get('transaction-hash/exists', 'ValidateTransactionHashController@checkTransactionHashIfExists');
Route::get('/all', 'MembershipController@index');
Route::get('/stats', 'MembershipStatsController@index');
Route::get('/downlines', 'MembershipStatsController@getUserMembershipDownline');
Route::get('/details', 'MembershipStatsController@getUserMembershipDetails');

Route::post('/confirm', 'ConfirmMembershipController@confirm');
Route::post('/deny', 'DenyMembershipController@deny');
Route::post('/paid', 'PaidMembershipController@markAsPaid');
Route::post('/unpaid', 'PaidMembershipController@markAsUnPaid');
Route::post('/pay', 'PaidMembershipController@pay');
