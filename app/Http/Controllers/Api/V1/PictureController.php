<?php

namespace Ogilo\Gallery\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Ogilo\Gallery\Models\Picture;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image',
            'albums.*' => 'required|exists:albums,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors()
            ]);
        }

        $picture = new Picture();
        $filename     = time() . '.jpg';
        $dir         = public_path('images/pictures');

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, TRUE);
        }

        if ($request->picture->isValid()) {
            $cat = PictureCategory::where('name', 'gallery')->first();

            $image = Image::make($request->picture->getRealPath());

            if (!File::exists($dir . '/original')) {
                File::makeDirectory($dir . '/original', 0755, TRUE);
            }

            $image->save($dir . '/original/' . $filename);

            // $image->fit($cat->max_width,$cat->max_height);
            $img = json_decode($request->input('image_cropdetails'));
            $image->crop((int) $img->width, (int) $img->height, (int) $img->x, (int) $img->y);

            $image->save($dir . '/' . $filename);
            $image->destroy();

            if (!File::exists($dir . '/thumbnails')) {
                File::makeDirectory($dir . '/thumbnails', 0755, TRUE);
            }

            $thumb = json_decode($request->input('thumbnail_cropdetails'));
            $thumbnail = Image::make($request->file('name')->getRealPath());
            $thumbnail->crop((int)$thumb->width, (int)$thumb->height, (int)$thumb->x, (int)$thumb->y);
            $thumbnail->save($dir . '/thumbnails/' . $filename);
            $thumbnail->destroy();

            $picture->name        = $filename;
            $picture->alt        = $request->input('alt');
            $picture->caption    = $request->input('caption');
            $picture->title        = $request->input('title');
            $picture->url        = $request->input('url');
            // $picture->picture_category_id 	= $request->input('picture_category');

            $cat->pictures()->save($picture);

            $picture->albums()->sync($request->albums);

            return response()->json([
                'success' => true,
                'message' => 'Picture created',
                'picture' => $picture
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Picture not created'
        ]);
    }
}
