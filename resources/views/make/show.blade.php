{{$at}}extends('layout')

{{$at}}section('title', '{{$routeName}}')

{{$at}}section('body')
	<div class="container">
@foreach( $columns as $column )

	@if(!empty($column->json))
		@if (!empty($column->json['table']))
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control" readonly="true"  name="{{$column->name}}" value="{{$doubleQ}} getTableValue('{{$column->json['table']}}', $data->{{ $column->name }}, '{{isset($column->json['id'])?$column->json['id']:'id'}}', '{{isset($column->json['name'])?$column->json['name']:'name'}}')}}" >
    	</div>
    	@else
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<select class="form-control" readonly="true"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" >
				@foreach($column->json as $value => $label)
				<option value="{{$value}}" {{$at}}if ($data->{{$column->name}} == '{{$value}}') "selected=selected" {{$at}}endif >{{$label}}</option>
				@endforeach
			</select>
    	</div>
    	@endif
   	@else
    	<div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control" readonly="true"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}  }}" >
    	</div>
	@endif

@endforeach
	<a  href="javascript:$('.modal').modal('hide');"  class="btn btn-outline-primary btn-sm float-right" role="buttton">返回</a>
	</div>
{{$at}}endsection
﻿