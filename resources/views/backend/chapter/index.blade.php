@extends('backend.layouts.master')
@section('title',$title . ' ' . $panel)
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route . 'create')}}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create</a>
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
        @include('backend.includes.flash_message')
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @forelse($data['rows'] as $index => $row)
                        @if(auth()->user()->id === $row->created_by)

                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$row->name}}</td>
                                <td>
                                    @if($row->status == 1)
                                        <span class="text text-success">Active</span>
                                    @else
                                        <span class="text text-danger">De-Active</span>
                                    @endif
                                </td>
                                <td>{{$row->created_at}}</td>
                                <td>
                                    <a href="{{route($base_route . 'edit',$row->id)}}" class="btn btn-info">Edit</a>
                                    {!! Form::open(['route' => [$base_route . 'destroy',$row->id],'method' => 'delete']) !!}
                                    {!! Form::submit('Delete ',['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
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
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
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
