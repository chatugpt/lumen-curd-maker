{{$at}}extends('layout')

{{$at}}section('title', '{{$routeName}}')

{{$at}}section('body')

<div class="container">

{{$at}}if ($data->id)
<form action="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}}$data->id}}" method="post">
{{$at}}else
<form action="/{{$adminPath}}/{{$routeName}}" method="post">
{{$at}}endif

@foreach( $columns as $column )
    @if (in_array($column->name, ['updated_at', 'deleted_at', 'salt']))
    	
	@elseif($column->prikey == 'PRI')
		<input type="hidden" name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}">
	@elseif(!empty($column->json))
		@if (!empty($column->json['table']))
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" >
    	</div>
    	@else
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<select class="form-control"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" >
				@foreach($column->json as $value => $label)
				<option value="{{$value}}" {{$at}}if ($data->{{$column->name}} == '{{$value}}') "selected=selected" {{$at}}endif >{{$label}}</option>
				@endforeach
			</select>
    	</div>
    	@endif
   	@else
   	    <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" >
    	</div>
	@endif
@endforeach

		<div class="form-group">
        {{$at}}if ($data->id)
        <button type="submit" class="btn btn-default">修改</button>
        {{$at}}else
        <button type="submit" class="btn btn-default">提交</button>
        {{$at}}endif
			
			<a style="float: right" href="/{{$adminPath}}/{{$routeName}}" class="btn btn-default">返回</a>
		</div>

</form>
</div>
{{$at}}endsection
﻿