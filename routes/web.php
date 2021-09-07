<?php

use Ogilo\AdminMd\Models\Page;
use Illuminate\Support\Facades\Route;

Route::get('gallery/photos', function () {
    $page = new Page;
    $page->title = "Gallery";
    return view('gallery::gallery', compact('page'));
})->name('gallery');
