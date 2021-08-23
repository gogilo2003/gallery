<?php

use Illuminate\Support\Facades\Route;
use Ogilo\Gallery\Http\Controllers\GalleryController;

Route::group(['middleware'=>'web','as'=>'admin','prefix'=>'admin','namespace'=>'Ogilo\AdminMd\Http\Controllers'],function(){

	Route::group(['middleware'=>'auth:admin'],function(){
        Route::get('gallery',[GalleryController::class,'dashboard'])->name('-gallery');
    });

});