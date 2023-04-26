<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\ChapterRequest;
use Illuminate\Http\Request;

use App\Models\Chapter;
use App\Models\Product;
use App\Models\Get;
use Illuminate\Support\Str;
use App\Models\ProductChapter;

class ChapterController extends BackendBaseController
{
    protected $panel = 'Chapter';  //for section/module
    protected $folder = 'backend.chapter.';  //for view file
    protected $base_route = 'backend.chapter.';  //for for route method
    protected $folder_name = 'chapter';
    protected $title;
    protected $model;
    function __construct(){
        $this->model = new Chapter();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'List';
        $data['rows'] = $this->model->get();
        return view($this->__loadDataToView($this->folder . 'index'),compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->title = 'Create';

        $data['products'] = Product::where('created_by',auth()->user()->id)->pluck('title','id');
        $data['numbers'] = Chapter::pluck('number','id');
        $data['favourites'] = Get::pluck('user','id');
        return view($this->__loadDataToView($this->folder . 'create'),compact('data'));
    }
    // public function allData(){
       
    //     $product_id = Input::

    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chapter = Chapter::whereNotNull('number')->where('product_id',$request->product_id)->pluck('number')->last();
        $addedChapters = $chapter+1;
        $user = Get::whereNotNull('user')->where('product_id', $request->product_id)->pluck('user')->all(); 
        $name = Product::whereNotNull('title')->where('id', $request->product_id)->pluck('title')->all();
        $replaced = implode(" ",$name);
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            "registration_ids" => $user,
            'notification' => [
                'title' => $replaced,
                'body' => "Chapter $addedChapters: $request->name has been released 📖",
                'content_available' => true,
                'priority' => 'high',
            ],
        ];
        $fields = json_encode ($data);
        $headers = array (
            'Authorization: key=' . "AAAAPy-n7Dg:APA91bHcJaVHKeU4KMJsF2dpA3cqOm-Biyeu_pWxQtOAwCoc7nmbAtybYvyyQOOObU8sdsXmRZ2BsLa2uq-ClH-DNT2_HajTUxOc4uvTtLrLELqI4dn9fwcG-Z3MvHgK7NO694U6t_cK",
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
        $request->request->add(['number'=>$addedChapters]);
        $request->request->add(['created_by'=>auth()->user()->id]);
        $data['row'] = $this->model->create($request->all());
        if ($data['row']){
            $request->session()->flash('success', $this->panel.' created successfully');
        } else{
            $request->session()->flash('error', $this->panel.' creation failed');
        }
        return redirect()->route($this->base_route.'index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['row'] = $this->model->find($id);
        if (!$data['row']){
            request()->session()->flash('error_message', $this->panel . ' record not found');
            return redirect()->route($this->base_route . 'index');
        }
        $this->title = 'View';
        return view($this->__loadDataToView($this->folder . 'show'),compact('data'));
    }

    public function all(){
        return Chapter::with('novel')->get();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['products'] = Product::pluck('name','id');
        $data['row'] = $this->model->find($id);
        if ($data['row']){
            request()->session()->flash('error_message', $this->panel . ' record not found');
        }
        $this->title = 'Edit';
        return view($this->__loadDataToView($this->folder . 'edit'),compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function partChapters($id){
        $data = Chapter::where('product_id', $id)->get();
       return $data;
    }
    public function update(ChapterRequest $request, $id)
    {
        $data['row'] = $this->model->find($id);
        $request->request->add(['updated_by' => auth()->user()->id]);
        if ($data['row']->update($request->all())){
            $request->session()->flash('success_message', $this->panel . ' updated successfully');
        } else{
            $request->session()->flash('error_message', $this->panel . ' update failed');
        }
        return redirect()->route($this->base_route . 'index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data['row'] = $this->model->find($id);
        if ($data['row']->delete()){
            request()->session()->flash('success_message', $this->panel . ' deleted successfully');
        } else{
            request()->session()->flash('error_message', $this->panel . ' deletation failed');
        }
        return redirect()->route($this->base_route . 'index');
    }
    /**
     * shows the deleted items of database
     */
    public function trash()
    {
        $this->title = 'Trash List';
        $data['rows'] = $this->model->onlyTrashed()->orderBy('deleted_at','desc')->get();
        return view($this->__loadDataToView($this->folder . 'trash'),compact('data'));
    }
    /**
     * restore the deleted item of database
     */
    public function restore($id)
    {
        $data['row'] = $this->model->where('id',$id)->withTrashed()->first();
        if ($data['row']->restore()){
            request()->session()->flash('success_message', $this->panel . ' restore successfully');
        } else{
            request()->session()->flash('error_message', $this->panel . ' restore failed');
        }
        return redirect()->route($this->base_route . 'index');
    }
    /**
     * delete the item from database permanently
     */
    public function forceDelete($id)
    {
        $data['row'] = $this->model->where('id',$id)->withTrashed()->first();
        if ($data['row']->forceDelete()){
            request()->session()->flash('success_message', $this->panel . ' permanent deleted successfully');
        } else{
            request()->session()->flash('error_message', $this->panel . ' permanent delete failed');
        }
        return redirect()->route($this->base_route . 'trash');
    }
    public function changeStatusById(Request $request, $id,$value)
    {
        $data['row'] = $this->model->find($id);
        $request->request->add(['updated_by' => auth()->user()->id]);
        $request->request->add(['status' => $value]);
        if ($data['row']->update($request->all())){
            $request->session()->flash('success_message', $this->panel . ' updated successfully');
        } else{
            $request->session()->flash('error_message', $this->panel . ' update failed');
        }
        return redirect()->route($this->base_route . 'index');
    }
  
    
}
