<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Requests\Backend\ProductRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductController extends BackendBaseController
{
    protected $panel = 'Product';  //for section/module
    protected $folder = 'backend.product.';  //for view file
    protected $base_route = 'backend.product.';  //for for route method
    protected $folder_name = 'product';
    protected $title;
    protected $model;
    function __construct(){
        parent::__construct();
        $this->model = new Product();
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
        $data['attributes'] = Attribute::all();
        return view($this->__loadDataToView($this->folder . 'create'),compact('data'));
    }

    //for api
    public function all(){
        return Product::with('chapters','createdBy','attributes','comments')->get();
    }
    public function recommended(){
        return Product::with('chapters','createdBy','attributes','comments')->where('feature_product' , 1)->get();
    }

    public function popular(){
        return Product::with('chapters','createdBy','attributes','comments')->where('flash_product' , 1)->get();
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
        $data['row']->feature_product = '1';
        $data['row']->save();
        return redirect()->back();
    }

    public function recommendedoff($id)
    {
        $data['row'] = $this->model->findOrFail($id);
        $data['row']->feature_product = '0';
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
    public function store(ProductRequest $request)
    {
//        dd($request);
if ($request->hasFile('image_file')) {
    $image = $this->uploadImage($request,'image_file');
    $request->request->add(['image' => $image]);
}
        $request->request->add(['created_by' => auth()->user()->id]);

        $data['row'] = $this->model->create($request->all());
        if ($data['row']){

            $attribute_id = $request->input('attribute_id');
            $attributeArray['product_id'] = $data['row']->id;
            $attributeArray['status'] = 1;

            for ($i = 0; $i < count($attribute_id); $i++) {
                $attributeArray['attribute_id'] = $attribute_id[$i];
                ProductAttribute::create($attributeArray);
            }
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
        //
        $data['row'] = Attribute::pluck('name','id');
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
    public function update(ProductRequest $request, $id)
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
        $novel = Product::find($request->input('novel_id'));
       $html = "<option value=''>Select Chapters</option>";
        foreach($novel->chapters as $district){
            $html .= "<option value='$district->id'>$district->number</option>";
        }
        return $html;
    }
    public  function  getDistrictByProvinceId (Request $request){
        $province = Product::find($request->input('product_id'));
       $html = "";
        foreach($province->favourites as $district){
            $html .= "''{$district->user}'',";
        }
        return $html;
    }
    // public  function  getDistrictByProvinceId (Request $request){
    //     $province = Product::find($request->input('product_id'));
    //    $html = "<option value=''>Select Favourites</option>";
    //     foreach($province->favourites as $district){
    //         $html .= "<option value='$district->id'>$district->user</option>";
    //     }
    //     return $html;
    // }
}
