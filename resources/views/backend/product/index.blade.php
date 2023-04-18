@extends('backend.layouts.master')
@section('title',$title )
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route . 'create')}}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create</a>
            <a href="{{route($base_route . 'index')}}" class="btn btn-primary"><i class="fas fa-list-alt"></i> list</a>

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
                        <th>S.N.</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @forelse($data['rows'] as $index => $row)
                        @if(auth()->user()->user === 0)
                        <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$row->title}}</td>
                           
                                <td>{{ Str::limit($row->description, 200, '...') }}</td>
                                <td>
                                    <img src="{{asset("images/product/$row->image")}}" alt="" height="150">
                                    </td>
                                    <td><div class="dropdown">

                                        @if($row->status == 0)
                                            <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Deactive
                                            </button>

                                        @else
                                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Active
                                            </button>

                                        @endif
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{route($base_route .'active',$row->id)}}">
                                                Active
                                            </a>
                                            <a class="dropdown-item" href="{{route($base_route .'deactive',$row->id)}}">Deactive</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{route($base_route . 'edit',$row->id)}}" class="btn btn-info" style="margin: 5px">Edit</a>
                                    {!! Form::open(['route' => [$base_route . 'destroy',$row->id],'method' => 'delete']) !!}
                                    {!! Form::submit('Delete ',['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                    <a href="{{route($base_route . 'show',$row->id)}}" class="btn btn-info" style="margin: 5px">View</a>
                                </td>
                            </tr>
                            @else
                        @if(auth()->user()->id === $row->created_by)

                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$row->title}}</td>
                           
                                <td>{{ Str::limit($row->description, 200, '...') }}</td>
                                <td>
                                    <img src="{{asset("images/product/$row->image")}}" alt="" height="150">
                                    </td>
                                    <td><div class="dropdown">

                                        @if($row->status == 0)
                                            <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Deactive
                                            </button>

                                        @else
                                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Active
                                            </button>

                                        @endif
                                    
                                    </div>
                                </td>
                                <td>
                                    <a href="{{route($base_route . 'edit',$row->id)}}" class="btn btn-info" style="margin: 5px">Edit</a>
                                    {!! Form::open(['route' => [$base_route . 'destroy',$row->id],'method' => 'delete']) !!}
                                    {!! Form::submit('Delete ',['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                    <a href="{{route($base_route . 'show',$row->id)}}" class="btn btn-info" style="margin: 5px">View</a>
                                </td>
                            </tr>
                            @endif
                            @endif
                        @empty
                            <tr class="text text-danger">
                                <td colspan="5">{{$panel}} not found</td>
                            </tr>
                        @endforelse
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>S.N.</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection