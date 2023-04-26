<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $userde=auth()->user()->id;
        
        return response()->json([
            'user_type' => auth()->user()->user,
            'paid' => auth()->user()->paid,
            'token' => $token,
            'message' => "successfull",
            'user_id' => auth()->user()->id,
            'user_email' => auth()->user()->email
        ]);
    
    }
    public function addFavourites(Request $request){
        try{
            $verif=User::find($request->id);
            $verif->paid = 1;
            $verif->save();
            return response()->json([
     
                'success' => true,        
                ]);
            }
            catch(e){
                return response()->json([
     
                    'success' => false,        
                    ]);
                }
            }

         public function addComment(Request $request){
            try{
             Comments::create([
                'product_id' => $request->product_id,
                'comments' => $request->comments,
                'likes' => $request->likes,
                "user_id" => $request->user_id,
            ]);
            return response()->json([
                'message' => "success",
                ]);
        }
        catch(e){
            return response()->json([
                'message' => "failed",
                ]);
        }
         }
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:3',
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => $errors
            ], 400);
        }
        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'user' => $request->user,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user_type' => $user->user,
                'paid' => 0,
                'token' => $token,
                'message' => "successfull",
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
        }
        else{
            return response()->json([
                'message' => "failed",
                ]);
            }
        }

}