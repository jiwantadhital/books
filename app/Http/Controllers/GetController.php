<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Get;
use App\Models\Comments;
use App\Models\Likes;

class GetController extends Controller
{
    public function addMultipleBooks(Request $request){
        if($request->isMethod('post')){
            $getData = $request->all();
                $get = new Get;
                $get->product_id = $request['product_id'];
                $get->user = $request['user'];
                $get->save();
            return response()->json(['success']);
        }
        return response()->json(['message'=>'Wew no']);
    }


    public function destroy($id)
    {
        return Get::destroy($id);
    }

    //for comments
    public function comments(Request $request){
        if($request->isMethod('post')){
            $getComment = $request->all();
            $comment = new Comments;
            $comment->product_id = $request['product_id'];
            $comment->comments = $request['comments'];
            $comment->likes = $request['likes'];
            $comment->save();
            return response()->json(['success']);
        }
    }
    public function getComment(){
        return Comments::all();
    }
    //for favourites
    public function favourites(){
        return Get::all();
    }



    //for likes
    public function getLikes(){
        $data = Likes::pluck('product_id');
        return Product::whereIn('id', $data->toArray())->with('chapters','createdBy','attributes','comments')->get();
    }
    public function addLikes(Request $request){
        if($request->isMethod('post')){
            $getLikes = $request->all();
            $likes = new Likes;
            $likes->product_id = $request['product_id'];
            $likes->liked = $request['liked'];
            $likes->name = $request['name'];
            $likes->image = $request['image'];
            $likes->save();
            return response()->json(['success']);
        }
    }
}
