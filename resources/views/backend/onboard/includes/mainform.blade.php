<div class="form-group">
    {!! Form::label('title','Title',['class' => 'control-label']) !!}
    {!! Form::text('title',null,['class' => 'form-control']) !!}
    @error('title')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('description','Description',['class' => 'control-label']) !!}
    {!! Form::text('description',null,['class' => 'form-control']) !!}
    @error('description')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('image_file','Image',['class' => 'control-label']) !!}
    {!! Form::file('image_file',null,['class' => 'form-control']) !!}
    @error('image_file')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('status','Status',['class' => 'control-label']) !!}
    {!! Form::radio('status',1) !!}Active
    {!! Form::radio('status',0,true) !!}De-Active
</div>
