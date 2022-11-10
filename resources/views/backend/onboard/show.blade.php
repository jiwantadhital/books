@extends('backend.layouts.master')
@section('title',$title . ' ' . $panel)
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route . 'index')}}" class="btn btn-primary"><i class="fas fa-list-alt"></i> List</a>
            <a href="{{route($base_route . 'trash')}}" class="btn btn-danger"><i class="fas fa-recycle"></i> Trash</a>
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            <tr>
                <th>Name</th>
                <td>{{$data['row']->name}}</td>
            </tr>

            <tr>
                <th>status</th>
                <td>
                    @if($data['row']->status)
                        <span class="text-success">Active</span>
                    @else
                        <span class="text-danger">De Active</span>
                    @endif
                </td>

            </tr>
            <tr>
                <th>Created At</th>
                <td>{{$data['row']->created_at}}</td>
            </tr><tr>
                <th>updated_at</th>
                <td>{{$data['row']->updated_at}}</td>
            </tr>
            <tr>
                <th>Created By</th>
                <td>{{$data['row']->createdBy->name}}</td>
            </tr>

            @if(!empty($data['row']->updated_by))
            <tr>
                <th>Updated By</th>
                <td>{{$data['row']->updatedBy->name}}</td>
            </tr>
            @endif

            </tbody>
        </table>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
