<?php 
namespace Ogilo\Gallery\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Ogilo\Gallery\Models\Album;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller 
{
    public function index($id=null)
    {
        $validator = Validator::make(['id'=>$id],[
            'id'=>'nullable|exists:albums'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'Validation Error',
                'details'=>$validator->errors()
            ]);
        }

        if($id){
            $album = Album::with('pictures')->find($id);
            return response()->json($album);
        }

        $albums = Album::with('pictures')->get();
        return response()->json($albums);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'=>'required|unique:albums'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'Validation Error',
                'details'=>$validator->errors()
            ]);
        }

        $album = new Album();
        $album->title = $request->title;
        $album->description = $request->description;
        $album->save();

        return response()->json([
            "success"=>true,
            "message"=>"Album created",
            "album"=>$album
        ]);

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'required|exists:albums',
            'title'=>'required|unique:albums,title,'.$request->id
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'Validation Error',
                'details'=>$validator->errors()
            ]);
        }

        $album = Album::find($request->id);
        $album->title = $request->title;
        $album->description = $request->description;
        $album->save();

        return response()->json([
            "success"=>true,
            "message"=>"Album updated",
            "album"=>$album
        ]);

    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'required|exists:albums'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'Validation Error',
                'details'=>$validator->errors()
            ]);
        }

        $album = Album::find($request->id);
        $album->delete();

        return response()->json([
            "success"=>true,
            "message"=>"Album deleted",
        ]);

    }
}
