<?php

namespace Ogilo\Gallery\Http\Controllers\Api\V1;

use Ogilo\Gallery\Models\Picture;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Ogilo\AdminMd\Models\PictureCategory;

class PictureController extends Controller
{
    public function index($id = null)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'nullable|exists:pictures'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors()
            ]);
        }

        if ($id) {
            $picture = Picture::with('albums')->find($id);
            return response()->json($picture);
        }

        $cat = PictureCategory::where('name', 'gallery')->first();
        $pictures = Picture::where('picture_category_id', $cat->id)->get();
        return response()->json($pictures);
    }
    
}
