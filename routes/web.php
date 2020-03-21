<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ProductController@index')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
