<?php
namespace Ogilo\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function dashboard()
    {
        return view('gallery::dashboard');
    }
}
