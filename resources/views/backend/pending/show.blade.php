@extends('backend.layouts.master')
@section('title',$title)
@section('main-content')
    <div class="card-header">
        <h3 class="card-title">{{$title}}
            <a href="{{route($base_route.'index')}}" class="btn btn-success">
                <i class="fas fa-list"></i>
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
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic View</a></li>
            <li class="nav-item"><a class="nav-link" href="#attributes" data-toggle="tab">Attributes View</a></li>
        </ul>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        @include('backend.includes.flash_message')
        <div class="tab-content">
            <div class="active tab-pane" id="basic">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Title</th>
                            <td>{{$data['row']->title}}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{$data['row']->description}}</td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td>
                                    <img src="{{asset('images/product/' .$data['row']->image)}}" alt="" height="150">
                                    </td>
                         </tr>
                         <th>Recommended Product</th>
                            <td>
                                <div class="dropdown">
                                    @if($data['row']->feature_product == 0)
                                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Off
                                        </button>

                                    @else
                                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            On
                                        </button>

                                    @endif
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{route($base_route .'recommendedon',$data['row']->id)}}">
                                            On
                                        </a>
                                        <a class="dropdown-item" href="{{route($base_route .'recommendedoff',$data['row']->id)}}">Off</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Flash Product</th><td>
                            <div class="dropdown">
                                @if($data['row']->flash_product == 0)
                                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Off
                                    </button>

                                @else
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        On
                                    </button>

                                @endif
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{route($base_route .'flashon',$data['row']->id)}}">
                                        On
                                    </a>
                                    <a class="dropdown-item" href="{{route($base_route .'flashoff',$data['row']->id)}}">Off</a>
                                </div>
                            </div>
                            </td>
                        </tr>
                        <tr>
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
                        @if (!empty($data['row']->updated_by))
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
                {{--basic view--}}
{{--                @include($folder. 'includes.basic_view')--}}
            </div>
            <div class="tab-pane" id="attributes">
{{--                @include($folder. 'includes.attributes_view')--}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Status</th>

            
                    </table>
                </div>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->

    <div class="card-footer">
        Footer
    </div>
    <!-- /.card-footer-->
@endsection
