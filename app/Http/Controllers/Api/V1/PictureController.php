<?php

namespace Ogilo\Gallery\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ogilo\AdminMd\Models\PictureCategory;
use Ogilo\Gallery\Models\Picture;

class PictureController extends Controller
{
    public function index($id = null)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'nullable|exists:pictures',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors(),
            ]);
        }

        if ($id) {
            $picture = Picture::with('albums')->find($id);

            return response()->json($picture);
        }

        $cat = PictureCategory::where('name', 'gallery')->first();

        $pictures = $cat ? Picture::with('albums')->where('picture_category_id', $cat->id)->get() : [];

        return response()->json($pictures);
    }

    public function create(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image',
            'albums.*' => 'required|exists:albums,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors(),
            ], 422);
        }

        $picture = new Picture();
        $filename = time().'.jpg';
        $dir = public_path('images/pictures');

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        if ($request->picture->isValid()) {
            $cat = PictureCategory::where('name', 'gallery')->first();
            if (!$cat) {
                $cat = new PictureCategory();
                $cat->name = 'gallery';
                $cat->title = 'Photo Gallery';
                $cat->save();
            }

            $image = Image::make($request->picture->getRealPath());

            if (!File::exists($dir.'/original')) {
                File::makeDirectory($dir.'/original', 0755, true);
            }

            $image->save($dir.'/original/'.$filename);

            // $image->fit($cat->max_width,$cat->max_height);
            $img = json_decode($request->image_cropdetails);
            $image->crop((int) $img->width, (int) $img->height, (int) $img->x, (int) $img->y);

            $image->save($dir.'/'.$filename);
            $image->destroy();

            if (!File::exists($dir.'/thumbnails')) {
                File::makeDirectory($dir.'/thumbnails', 0755, true);
            }

            $thumb = json_decode($request->thumbnail_cropdetails);
            $thumbnail = Image::make($request->picture->getRealPath());
            $thumbnail->crop((int) $thumb->width, (int) $thumb->height, (int) $thumb->x, (int) $thumb->y);
            $thumbnail->save($dir.'/thumbnails/'.$filename);
            $thumbnail->destroy();

            $picture->name = $filename;
            $picture->alt = $request->alt;
            $picture->caption = $request->caption;
            $picture->title = $request->title;
            $picture->url = $request->url;
            // $picture->picture_category_id = $request->picture_category;

            $cat->pictures()->save($picture);

            if ($request->has('albums')) {
                $albums = is_null($request->albums) ? null : (is_array($request->albums) ? $request->albums : explode(',', $request->albums));

                if (!is_null($albums)) {
                    $picture->albums()->sync($albums);
                }
                $picture->load('albums');
            }

            return response()->json([
                'success' => true,
                'message' => 'Picture created',
                'picture' => $picture,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'An error occured and picture not created. Please try again later',
        ], 500);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'nullable|image',
            'url' => 'nullable|url',
            'id' => 'required|exists:pictures',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $picture = Picture::find($request->id);

        if ($request->hasFile('picture')) {
            $filename = $picture->name ? $picture->name : time().'.jpg';
            $dir = public_path('images/pictures');

            // Image::configure(array('driver' => 'imagick'));

            $image = Image::make($request->picture->getRealPath());

            if (!File::exists($dir.'/original')) {
                File::makeDirectory($dir.'/original', 0755, true);
            }

            $image->save($dir.'/original/'.$filename);

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $cat = PictureCategory::find($request->picture_category);
            // $image->fit($cat->max_width,$cat->max_height);

            $img = json_decode($request->image_cropdetails);
            $image->crop((int) $img->width, (int) $img->height, (int) $img->x, (int) $img->y);
            $image->save($dir.'/'.$filename);
            $image->destroy();

            if (!File::exists($dir.'/thumbnails')) {
                File::makeDirectory($dir.'/thumbnails', 0755, true);
            }

            $thumb = json_decode($request->thumbnail_cropdetails);
            $thumbnail = Image::make($request->picture->getRealPath());
            $thumbnail->crop((int) $thumb->width, (int) $thumb->height, (int) $thumb->x, (int) $thumb->y);
            $thumbnail->save($dir.'/thumbnails/'.$filename);
            $thumbnail->destroy();

            $picture->name = $filename;
            // dd($request->picture);
        }

        $picture->alt = $request->alt;
        $picture->caption = $request->caption;
        $picture->title = $request->title;
        $picture->url = $request->url;

        $picture->save();

        $albums = is_null($request->albums) ? null : (is_array($request->albums) ? $request->albums : explode(',', $request->albums));

        if (!is_null($albums)) {
            $picture->albums()->sync($albums);
        }
        $picture->load('albums');

        return response()->json([
            'success' => true,
            'message' => 'Picture updated',
            'picture' => $picture,
        ], 202);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:pictures',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors(),
            ], 422);
        }

        $picture = Picture::find($request->id);
        $picture->delete();

        return response()->json(['success' => true, 'message' => 'Picture deleted'], 204);
    }

    /**
     * Publish/Unpublish picture.
     *
     * @return JsonResponse
     */
    public function publish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:pictures',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors(),
            ], 422);
        }

        $picture = Picture::find($request->id);
        $picture->published = $picture->published ? 0 : 1;
        $picture->save();

        return response()->json([
            'success' => true,
            'message' => 'Picture '.($picture->published ? 'Published' : 'Unpublished'),
            'picture' => $picture,
        ], 202);
    }
}
