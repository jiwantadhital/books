@extends('backend.layouts.master')
@section('title',$title)
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route.'index')}}" class="btn btn-success">
                <i class="fas fa-plus-square"></i>
                List
            </a>
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
        @include('backend.includes.flash_message')
        <div class="table-responsive">
            <table class="table table-bordered">
               <tr>
                   <th>Name</th>
                   <td>{{$data['row']->name}}</td>
               </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($data['row']->status == 1)
                            <span class="text text-success">Active</span>
                        @else
                            <span class="text text-danger">De Active</span>
                        @endif
                    </td>
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
                <tr>
                    <th>Created At</th>
                    <td>{{$data['row']->created_at}}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{$data['row']->updated_at}}</td>
                </tr>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
