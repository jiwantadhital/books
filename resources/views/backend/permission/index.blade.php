@extends('backend.layouts.master')
@section('title',$title)
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route.'create')}}" class="btn btn-success">
                <i class="fas fa-plus-square"></i>
                Add
            </a>
            <a href="{{route($base_route.'trash')}}" class="btn btn-danger">
                <i class="fa fa-trash"></i>
                Trash
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
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($data['rows'] as $index => $row)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$row->name}}</td>
                        <td>
                            @if($row->status == 1)
                                <span class="text text-success">Active</span>
                            @else
                                <span class="text text-danger">De Active</span>
                            @endif
                        </td>
                        <td>{{$row->created_at}}</td>
                        <td>
                            <a href="{{route($base_route . 'edit',$row->id)}}" class="btn btn-info">Edit</a>
                            {{-- Delete--}}
                            {!! Form::open(['route' => [$base_route . 'destroy',$row->id],'method'=>'post']) !!}
                            {!! Form::hidden('_method','DELETE') !!}
                            {!! Form::submit('Delete',['class'=>'btn btn-danger']) !!}
                            {!! Form::close() !!}
                            {{-- Delete ends --}}
                            <a href="{{route($base_route . 'show',$row->id)}}" class="btn btn-info">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text text-danger">{{$panel}} not found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
