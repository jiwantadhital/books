<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class DashboardController extends BackendBaseController
{
    protected $panel = 'Dashboard';    //for section/module
    protected $folder = 'backend.dashboard.';   // for view file
    protected  $base_route = 'backend.dashboard.';  // for route method
    protected  $title;
    protected $folder_name = 'dashboard';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data['total_products'] = Product::count();
        // $data['total_categories'] = Category::count();
        // $data['total_role'] = Role::count();
        // $data['total_subcategories'] = Subcategory::count();

        $this->title = 'List';
        return view($this->__loadDataToView($this->folder .'index'));

        // return view($this->__loadDataToView($this->folder .'index'),compact('data'));
    }
}
