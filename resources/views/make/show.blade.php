{{$at}}extends('layout')

{{$at}}section('title', '{{$routeName}}')

{{$at}}section('body')
	<div class="container">
@foreach( $columns as $column )

	@if(!empty($column->json))
		@if (!empty($column->json['table']))
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control" readonly="true"  name="{{$column->name}}" value="{{$doubleQ}} getTableValue('{{$column->json['table']}}', $data->{{ $column->name }}, '{{$column->json['id']}}', '{{$column->json['name']}}')}}" >
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
	<a  href="/{{$adminPath}}/{{$routeName}}" class="btn btn-outline-secondary">返回</a>
	</div>
{{$at}}endsection