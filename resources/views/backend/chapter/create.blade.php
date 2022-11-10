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
        {!! Form::open(['route' => $base_route . 'store','method' => 'post']) !!}
        @include($folder . 'includes.mainform')
        {!! Form::submit('Save ' . $panel,['class' => 'btn btn-info']) !!}
        {!! Form::close() !!}
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
@section('js')
    @include($folder . 'includes.add_row_script')
    @include($folder . 'includes.script')
@endsection
