<?php

namespace App\Http\Controllers;

use App\Models\cr;
use App\Models\Get;
use Illuminate\Http\Request;
use App\Models\PushNotification;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $push_notifications = PushNotification::orderBy('created_at', 'desc')->get();
        return view('notification.index', compact('books'));
    }
    public function bulksend(Request $req){
        // $comment = new PushNotification();
        // $comment->title = $req->input('title');
        // $comment->body = $req->input('body');
        // $comment->img = $req->input('img');
        // $comment->save();
        $ids = $req->product_id;
        $user = Get::whereNotNull('user')->where('product_id', $ids)->pluck('user')->all();     
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            "registration_ids" => $user,
            'notification' => [
                'title' => $req->title,
                'body' => 'New Chapter',
                'content_available' => true,
                'priority' => 'high',
            ],
        ];
        $fields = json_encode ($data);
        $headers = array (
            'Authorization: key=' . "AAAAhnZcE_g:APA91bHO0E9u4EAqd7OTej3MTJ9kZr2wMcHUg8yVFCAz2OkJNIDo_05pQOB8YDKVb-Ck0EgkMZxsTUxTY3-GqsqpLs2q4LxawFvdzdV51z70GeUZrk_Jgl9626V8Lk5by3OI5ODulQBO",
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        //var_dump($result);
        curl_close ( $ch );
        return redirect()->back()->with('success', 'Notification Send successfully');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notification.create');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotification $pushNotification)
    {
        //
    }
}