<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RoleRequest;
use App\Http\Requests\Backend\UnitRequest;
use App\Models\Module;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Http\Request;

class RoleController extends BackendBaseController
{
    protected $panel = 'Role'; //for section module
    protected $folder = 'backend.role.'; //for view file
    protected $base_route= 'backend.role.'; //for route method
    protected $title;
    protected $model;

    function __construct()
    {
        $this->model = new Role();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'List';
        $data['rows'] = $this->model->all();
        return view($this->__loadDataToView($this->folder.'index'),compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Add';
        return view($this->__loadDataToView($this->folder.'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
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
            request()->session()->flash('error', 'Invalid request');
            return redirect()->route($this->folder.'index');
        }
        $this->title = 'View';
        return view($this->__loadDataToView($this->folder.'show'),compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['row'] = $this->model->find($id);
        if(!$data['row']){
            request()->session()->flash('error_message', $this->panel.' record not found');
        }
        $this->title = 'Edit';
        return view($this->__loadDataToView($this->folder.'edit'),compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $data['row'] = $this->model->find($id);
        $request->request->add(['updated_by'=>auth()->user()->id]);
        if ($data['row']->update($request->all())){
            $request->session()->flash('success', $this->panel.' updated successfully');
        } else{
            $request->session()->flash('error', $this->panel.' update failed');
        }
        return redirect()->route($this->base_route.'index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data['row']  = $this->model->find($id);
        if ($data['row']->delete()){
            request()->session()->flash('success', $this->panel.' deleted successfully');
        } else{
            request()->session()->flash('error', $this->panel.' delete failed');
        }
        return redirect()->route($this->base_route.'index');
    }

    public function trash(){
        $this->title = 'Trash List';
        $data['rows'] = $this->model->onlyTrashed()->orderby('deleted_at','desc')->get();
        return view($this->__loadDataToView($this->folder.'trash'),compact('data'));
    }

    public function restore($id){
        $data['row'] = $this->model->where('id',$id)->withTrashed()->first();

        if ($data['row']->restore()){
            request()->session()->flash('success', $this->panel.' restored successfully');
        } else{
            request()->session()->flash('error', $this->panel.' restore failed');
        }
        return redirect()->route($this->base_route.'index');
    }

    public function forceDelete($id){
        $data['row'] = $this->model->where('id',$id)->withTrashed()->first();
        if ($data['row']->forceDelete()){
            request()->session()->flash('success', $this->panel.' premanently deleted');
        } else{
            request()->session()->flash('error', $this->panel.' delete failed');
        }
        return redirect()->route($this->base_route.'trash');
    }

    function assignPermission($roleId){
        $data['row'] = Role::find($roleId);

        /* to get assigned permission*/
        $data['permissions'] = $data['row']->permissions()->get();
        $assigned_permission = [];
        foreach($data['permissions'] as $permission){
            array_push($assigned_permission,$permission->id);
        }
        $data['assigned_permission'] = $assigned_permission;
        /*end of assigned permission*/

        $data['modules'] = Module::all();
        $this->title = 'Assign permission';
        return view($this->__loadDataToView($this->folder.'assign_permission'),compact('data'));
    }

    function postPermission(Request $request){
        $data['row'] = Role::find($request->input('role_id'));
        $data['row']->permissions()->sync($request->input('permission_id'));
        return redirect()->route($this->base_route.'index');

    }
}
