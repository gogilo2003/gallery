<?php

use Illuminate\Support\Facades\Route;


Route::get('gallery',function(){
    return view('gallery::index');
});