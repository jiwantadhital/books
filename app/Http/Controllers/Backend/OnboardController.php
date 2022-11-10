<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Requests\Backend\OnboardRequest;
use App\Models\Attribute;
use App\Models\Onboard;
use App\Models\ProductAttribute;
use GuzzleHttp\Psr7\Request;
use Intervention\Image\Facades\Image;

class OnboardController extends BackendBaseController
{
    protected $panel = 'Onboard';  //for section/module
    protected $folder = 'backend.onboard.';  //for view file
    protected $base_route = 'backend.onboard.';  //for for route method
    protected $folder_name = 'onboard';
    protected $title;
    protected $model;
    function __construct(){
        parent::__construct();
        $this->model = new Onboard();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->title = 'List';
//        $data['rows'] = $this->model->where('status',1)->get();
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
        return view($this->__loadDataToView($this->folder . 'create'));
    }
    public function all(){
        return Onboard::get();
    }

    public function active($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->status = '1';
        $data['row']->save();
        return redirect()->back();
    }

    public function deactive($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->status = '0';
        $data['row']->save();
        return redirect()->back();
    }

    public function recommendedon($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->recommended_product = '1';
        $data['row']->save();
        return redirect()->back();
    }

    public function recommendedoff($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->recommended_product = '0';
        $data['row']->save();
        return redirect()->back();
    }

    public function flashon($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->flash_product = '1';
        $data['row']->save();
        return redirect()->back();
    }

    public function flashoff($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->flash_product = '0';
        $data['row']->save();
        return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OnboardRequest $request)
    {
//        dd($request);
        if ($request->hasFile('image_file')) {
            $image = $this->uploadImage($request,'image_file');
         $request->request->add(['image' => $image]);
                    }           
            $request->request->add(['created_by'=>auth()->user()->id]);
            $data['row'] = $this->model->create($request->all());
            if ($data['row']){
            $request->session()->flash('success', $this->panel.' created successfully');
                } else{
             $request->session()->flash('error', $this->panel.' creation failed');
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
        //
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
        //
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
    public function update(OnboardRequest $request, $id)
    {
        $data['row'] = $this->model->find($id);
        if ($request->hasFile('image_file')) {
            $image = $this->uploadImage($request,'image_file');
            $request->request->add(['image' => $image]);
            if ($image){
                $this->deleteImage($data['row']->image);
            }
        }
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
        //
        $data['row'] = $this->model->find($id);
        if ($data['row']->delete()){
            request()->session()->flash('success_message', $this->panel . ' deleted successfully');
        } else{
            request()->session()->flash('error_message', $this->panel . ' deletation failed');
        }
        return redirect()->route($this->base_route . 'index');
    }

    function  getAllAttribute (){
       $data =  Attribute::pluck('name','id');
       return json_encode($data);
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
    public  function  getChapterByNovelId (Request $request){
        $novel = Onboard::find($request->input('novel_id'));
       $html = "<option value=''>Select Chapters</option>";
        foreach($novel->chapters as $district){
            $html .= "<option value='$district->id'>$district->number</option>";
        }
        return $html;
    }
}
