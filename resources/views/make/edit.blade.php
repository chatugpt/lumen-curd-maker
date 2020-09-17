{{$at}}extends('layout')

{{$at}}section('title', '{{$routeName}}')

{{$at}}section('body')

<div class="container">

<form action="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}} ($data->id) ? ('/'. $data->id):'' }}" method="post" onsubmit="return checkForm(this);">

@foreach( $columns as $column )
    @if (in_array($column->name, ['created_at', 'updated_at', 'deleted_at', 'salt']))

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
			<label class="submit"></label>
			<div>
    		<a href="/{{$adminPath}}/{{$routeName}}" class="btn btn-outline-secondary">返回</a>
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
    function   checkForm(dom){
        let data = $(dom).serialize();
        $.post('/{{$adminPath}}/{{$routeName}}{{$doubleQ}} ($data->id) ? ('/'. $data->id):'' }}', data, (res)=>{
            $(".invalid-feedback").remove();
            $("input").removeClass('is-invalid');

            if(typeof(res.status) !='undefined' && res.status == 0)
            {
                return history.go(-1)
            }
            else{
                if(res.data )
                {

                    for(let i in res.data) {
                        let field = i;
                        let error = res.data[i];

                        let input = $("input[name="+field+"]");
                        input.addClass('is-invalid');
                        let next = input.next('div');
                        if(!next.length)
                        {
                            input.after('<div class="invalid-feedback">'+error.join('<br />')+'</div>')
                        }
                        else{
                            next.html(error.join('<br />'))
                        }
                    }
                }
            }
        })
        console.info(data);
        return false;
    }
</script>
{{$at}}endsection
﻿
