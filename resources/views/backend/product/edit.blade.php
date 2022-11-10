@extends('backend.layouts.master')
@section('title',$title )
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route . 'index')}}" class="btn btn-primary"><i class="fas fa-list-alt"></i> List</a>
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
{{--        {!! Form::open(['route' => $base_route . 'store','method' => 'post']) !!}--}}
        {!! Form::model($data['row'],['route' => [$base_route . 'update',$data['row']->id],'method' => 'put','files' => true]) !!}
        @include($folder . 'includes.mainform')
        {!! Form::submit('Update ' . $panel,['class' => 'btn btn-info']) !!}
        {!! Form::close() !!}
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
