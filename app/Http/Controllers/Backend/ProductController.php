<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Requests\Backend\ProductRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\UserLiked;
use App\Models\UserPref;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;


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

    // public function popular(){
    //     return Product::with('chapters','createdBy','attributes','comments')->where('flash_product' , 1)->get();
    // }
   public function popular(){
    return Product::with('chapters','createdBy','attributes','comments')
        ->orderBy('favourite','DESC')
        ->take(3)
        ->get();
}

    //for popular
    public function addFavourites(Request $request){
        try{
            $verif=Product::find($request->id);
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

            

            public function storeData(Request $request)
            {
                $user_id = $request->input('user_id');
                $product_id = $request->input('product_id');
                $attribute_id = $request->input('attribute_id');
            
                // save the data to the database
                foreach ($attribute_id as $attr_id) {
                    DB::table('user_pref')->insert([
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'attribute_id' => $attr_id
                    ]);
                }
            
                return response()->json(['message' => 'Product details saved.']);
            }

            //delete likes
            public function deleteLikes(Request $request)
{
    if (DB::table('user_like')->where('user_id', $request->user_id)->where('product_id', $request->product_id)->exists()) {
        DB::table('user_like')->where('user_id', $request->user_id)->where('product_id', $request->product_id)->delete();
        return response()->json(['message' => 'Product details deleted.']);
    } else {
        return response()->json(['message' => 'Product details not found.']);
    }
}
            public function storeLike(Request $request)
            {
                $user_id = $request->input('user_id');
                $product_id = $request->input('product_id');
                if(!DB::table('user_like')->where('user_id', $user_id)->where('product_id', $product_id)->exists()) {
                    DB::table('user_like')->insert([
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                    ]);
                    return response()->json(['message' => 'Product details saved.']);

                }
                return response()->json(['message' => 'Error']);
                
            }

            public function showLiked($id){
                $data = UserLiked::with('products')->where('user_id',$id)->get();
                return $data;
            }
            //single product
            public function singleProduct($id){
                $data= Product::with('chapters','createdBy','attributes','comments')->where('id',$id)->get();
                if (count($data) > 0) {
                    return response()->json($data[0]);
                } else {
                    return response()->json([]);
                }
            }
            public function showUserData()
            {
                $mostRepeatedProductId = UserPref::select('product_id')
                    ->groupBy('product_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->value('product_id');
        $data = Product::where('id', $mostRepeatedProductId)->value('id');
                return "'$data'";
            }
          public function cosine_similarity($a, $b) {
                $a = array_count_values(str_word_count(strtolower($a), 1));
                $b = array_count_values(str_word_count(strtolower($b), 1));
                $dot_product = 0;
                foreach ($a as $word => $count) {
                    if (isset($b[$word])) {
                        $dot_product += $count * $b[$word];
                    }
                }
                $a_norm = sqrt(array_sum(array_map(function($x) {return $x * $x;}, $a)));
                $b_norm = sqrt(array_sum(array_map(function($x) {return $x * $x;}, $b)));
                return $dot_product / ($a_norm * $b_norm);
            }
            
            public function shoUData(){
                $product = Product::find(2);
                $description = $product->description;
                
                // 2. Calculate the cosine similarity between the description of product 3 and the descriptions of all other products in the database
                $products = Product::where('id', '<>', 2)->get();
                $similarities = [];
                foreach ($products as $p) {
                    $similarity = $this->cosine_similarity($description, $p->description);
                    $similarities[$p->id] = $similarity;
                }
                
                // 3. Select the top three products with the highest cosine similarity scores
                arsort($similarities);
                $similar_product_ids = array_slice(array_keys($similarities), 0, 4);
                $similar_products = Product::whereIn('id', $similar_product_ids)->get();
                return $similar_products;
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
