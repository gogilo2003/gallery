<?php

use Illuminate\Support\Facades\Route;
use Ogilo\Gallery\Models\Album;
use Ogilo\Gallery\Models\Picture;

Route::group(['middleware' => 'api', 'as' => 'api', 'prefix' => 'api', 'namespace' => 'Ogilo\Gallery\Http\Controllers\Api\V1'], function () {
    Route::group(['middleware' => 'auth:api', 'as' => '-admin', 'prefix' => 'admin'], function () {
        Route::group(['as' => '-albums', 'prefix' => 'albums'], function () {
            Route::get('{id?}', ['as' => '', 'uses' => 'AlbumController@index']);
            Route::post('', ['as' => '-create', 'uses' => 'AlbumController@create']);
            Route::patch('', ['as' => '-update', 'uses' => 'AlbumController@update']);
            Route::delete('', ['as' => '-delete', 'uses' => 'AlbumController@delete']);
        });

        Route::group(['as' => '-pictures', 'prefix' => 'pictures'], function () {
            Route::get('{id?}', ['as' => '', 'uses' => 'PictureController@index']);
            Route::post('', ['as' => '-create', 'uses' => 'PictureController@create']);
            Route::post('update', ['as' => '-update', 'uses' => 'PictureController@update']);
            Route::delete('', ['as' => '-delete', 'uses' => 'PictureController@delete']);
            Route::post('publish', ['as' => '-publish', 'uses' => 'PictureController@publish']);
        });
    });

    Route::prefix('gallery')
        ->name('-gallery')
        ->group(function () {
            Route::get('albums', function () {
                $albums = Album::get();

                return response()->json($albums, 200);
            })->name('-albums');

            Route::get('pictures/{id?}', function ($id = null) {
                $pictures = [];
                if ($id) {
                    $album = Album::with(['pictures' => function (Illuminate\Database\Schema\Blueprint $query) {
                        return $query->where('published', 1);
                    }])->find($id);
                } else {
                    $pictures = Picture::where('published', 1)->paginate(4);
                }

                return response()->json($pictures, 200);
            })->name('-pictures');
        });
});
