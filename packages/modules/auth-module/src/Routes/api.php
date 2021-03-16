<?php

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('admin/login', 'AuthAdminController@login');
