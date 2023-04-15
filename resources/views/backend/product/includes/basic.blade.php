<div class="form-group">
      <label>Categories</label>
      <td><select name="attribute_id[]" class="form-control selectpicker" multiple data-live-search="true">
          <option value="" disabled>Select Attribute</option>
          @foreach($data['attributes'] as $attribute)
            <option value="{{$attribute->id}}">{{$attribute->name}}</option>
          @endforeach
        </select></td>
</div>
<div class="form-group">
  {!! Form::label('title','Title',['class' => 'control-label']) !!}
  {!! Form::text('title',null,['class' => 'form-control']) !!}
  @error('title')
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
  {!! Form::label('description','Description',['class' => 'control-label']) !!}
  {!! Form::textarea('description',null,['class' => 'form-control','rows' => 3]) !!}
  @error('description')
  <span class="text text-danger">{{$message}}</span>
  @enderror
</div>
<div class="form-group">
  {!! Form::label('flash_product','Premium',['class' => 'control-label']) !!}
  {!! Form::radio('flash_product',1) !!}Premium
  {!! Form::radio('flash_product',0,true) !!}Free
</div>
<div class="form-group">
  {!! Form::label('status','Status',['class' => 'control-label']) !!}
  {!! Form::radio('status',1) !!}Active
  {!! Form::radio('status',0,true) !!}De-Active
</div>

