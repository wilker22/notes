<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    echo "Hello world";
});

Route::get('/about', function () {
    echo "About us";
});
