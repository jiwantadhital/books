<div class="form-group">
    {!! Form::label('product_id', 'Novels') !!}
    {!! Form::select('product_id', $data['products'], null,['class' => 'form-control']) !!}
    @error('product_id')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('number','Chapter Number',['class' => 'control-label']) !!}
    {!! Form::text('number',null,['class' => 'form-control','placeholder'=>'Chapters number are automatically updated starting from 1','disabled'=>'true']) !!}
    @error('number')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('name','Chapter Name',['class' => 'control-label']) !!}
    {!! Form::text('name',null,['class' => 'form-control']) !!}
    @error('name')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('description','Description',['class' => 'control-label']) !!}
    {!! Form::textarea('description',null,['class' => 'form-control']) !!}
    @error('description')
    <span class="text text-danger">{{$message}}</span>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('status','Status',['class' => 'control-label']) !!}
    {!! Form::radio('status',1) !!}Active
    {!! Form::radio('status',0,true) !!}De-Active
</div>
