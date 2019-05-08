{{$at}}extends('layout')

{{$at}}section('title', '{{$routeName}}')

{{$at}}section('body')

<div class="container">

{{$at}}if ($data->id)
<form action="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}}$data->id}}" method="post" class="needs-validation" novalidate onsubmit="return formCheck(this);">
{{$at}}else
<form action="/{{$adminPath}}/{{$routeName}}" method="post" class="needs-validation" novalidate onsubmit="return formCheck(this);">
{{$at}}endif

@foreach( $columns as $column )
    @if (in_array($column->name, ['created_at', 'updated_at', 'deleted_at', 'salt']))
    	
	@elseif($column->prikey == 'PRI')
		<input type="hidden" name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}">
	@elseif(!empty($column->json))
		@if (!empty($column->json['table']))
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<select class="form-control"  name="{{$column->name}}" {!! $column->h5FormValidatRule !!}>
	{{$doubleQ}} $tableData = getTableData('{{$column->json['table']}}','{{isset($column->json['id'])?$column->json['id']:'id'}}', '{{isset($column->json['name'])?$column->json['name']:'name'}}')}}
				{{$at}}foreach($tableData as $id => $value)
				<option value="{{$doubleQ}}$id}}" {{$at}}if ($data->{{$column->name}} == '{{$doubleQ}}$id}}') selected="selected" {{$at}}endif >{{$doubleQ}}$value}}</option>
				{{$at}}endforeach
			</select>
			<div class="invalid-feedback">请输入正确{{$column->remark ? $column->remark : $column->name}}</div>
    	</div>
    	@else
        <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<select class="form-control"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" >
				@foreach($column->json as $value => $label)
				<option value="{{$value}}" {{$at}}if ($data->{{$column->name}} == '{{$value}}') selected="selected" {{$at}}endif >{{$label}}</option>
				@endforeach
			</select>
			<div class="invalid-feedback">请选择{{$column->remark ? $column->remark : $column->name}}</div>
    	</div>
    	@endif
   	@else
   	    <div class="form-group">
			<label class="">{{$column->remark ? $column->remark : $column->name}}</label>
			<input type="text" class="form-control"  name="{{$column->name}}" value="{{$doubleQ}}$data->{{$column->name}}}}" {!! $column->h5FormValidatRule !!}>
			<div class="invalid-feedback">请输入正确{{$column->remark ? $column->remark : $column->name}}</div>
    	</div>
	@endif
@endforeach

		<div class="form-group">
			<label class="submit"></label>
			<div>
    		<a href="javascript:$('.modal').modal('hide');" class="btn btn-outline-secondary">返回</a>
            {{$at}}if ($data->id)
            <button type="submit" class="btn btn-primary float-right">修改</button>
            {{$at}}else
            <button type="submit" class="btn btn-primary float-right">提交</button>
            {{$at}}endif
            </div>
			

		</div>

</form>
</div>
<script>
function formCheck(dom){
	
	if(dom.checkValidity() === false)
	{
		dom.classList.add('was-validated');
		return false;
	}
	return true;
}
</script>
{{$at}}endsection
﻿