<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\AttributeRequest;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;

class AttributeController extends BackendBaseController
{
    protected $panel = 'Attribute';  //for section/module
    protected $folder = 'backend.attribute.';  //for view file
    protected $base_route = 'backend.attribute.';  //for for route method
    protected $folder_name = 'attribute';
    protected $title;
    protected $model;
    function __construct(){
        $this->model = new Attribute();
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
        $data['products'] = Product::pluck('title','id');
//        dd($data['products']);
        return view($this->__loadDataToView($this->folder . 'create'),compact('data'));
    }
    public function showAll(){
        return Attribute::with('novel')->get();
    }
    public function addFavourites(Request $request){
        try{
            $verif=Attribute::find($request->id);
            $verif->favourite = $verif->favourite+1;
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
            public function popular(){
                return Attribute::with('novel')
                    ->orderBy('favourite','DESC')
                    ->take(4)
                    ->get();
            }
    public function showId($id){
        $data = Attribute::with('novel')->where('id',$id)->get();
          if (count($data) > 0) {
            return response()->json($data[0]);
        } else {
            return response()->json([]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        $request->request->add(['created_by' => auth()->user()->id]);
        $data['row'] = $this->model->create($request->all());
        if ($data['row']){
            $request->session()->flash('success_message', $this->panel . ' created successfully');
        } else{
            $request->session()->flash('error_message', $this->panel . ' creation failed');
        }
        return redirect()->route($this->base_route . 'index');
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
    public function update(AttributeRequest $request, $id)
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
}
