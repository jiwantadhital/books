@extends('backend.layouts.master')
@section('title',$title)
@section('main-content')

    {!! Form::open(['route'=>$base_route.'store','method'=>'post','files' => true]) !!}
        <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic Form</a></li>
            </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
                <div class="active tab-pane" id="basic">
                   {{--basic form--}}
                    @include($folder. 'includes.basic')
                </div>
            
                <!-- /.tab-pane -->
               
                <!-- /.tab-pane -->
            
            <!-- /.tab-content -->
        </div><!-- /.card-body -->
        <div class="card-footer">
            {!! Form::submit('Save',['class'=>'btn btn-success']) !!}
        </div>
    {!! Form::close() !!}
    <!-- /.card-footer-->
@endsection
@section('js')
    @include($folder . 'includes.add_row_script')
    @include($folder . 'includes.script')
@endsection
