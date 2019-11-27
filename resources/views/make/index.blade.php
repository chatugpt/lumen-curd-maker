{{$at}}extends('layout')

{{$at}}section('title', '{{$table}}')

{{$at}}section('body')

<div class="container-fluid">
    <a  href="/{{$adminPath}}/{{$routeName}}/create/"  class="btn btn-primary float-right">添加</a>
    <form class="form-inline">
    	<input type="hidden" name="order_by" value="{{$doubleQ}}$orderBy}}">
      <div class="form-group">
    	<select class="form-control" name="search_field">
    @foreach ($columns as $column)
    <option value="{{$column->name}}" {{$at}}if ($searchField == '{{$column->name}}') selected="selected" {{$at}}endif>{{$column->remark ? $column->remark : $column->name}}</option>
    @endforeach
    	</select>
      </div>
    <div class="form-group mx-sm-3">
    	<input type="text" class="form-control" name="search"  value="{{$doubleQ}}$search}}">
    </div>

    <div class="form-group">
    	<button type="submit" class="btn btn-primary">搜索</button>
    </div>
	</form>
</div>
<br />
<div class="container-fluid">
	<div class="table-responsive">
	  <table class="table table-hover">
	  	<caption>{{$table}}</caption>
		<thead>
	        <tr>
    @foreach ($columns as $column)
    @if (!in_array($column->name, ['updated_at', 'deleted_at', 'salt', 'password']))
    <th id="thead_{{$column->name}}" class="sort"><span class="{{$at}}if ( '{{$column->name}}' == $sort[0]) {{$doubleQ}}$sort[1]}} {{$at}}endif"></span>{{$column->remark ? $column->remark : $column->name}}</th>
    @endif
    @endforeach
	        <th>操作</th>
	        </tr>
	     </thead>

    {{$at}}foreach ($records as $record)
	     	<tr>
    @foreach ($columns as $column)
    	@if (!in_array($column->name, ['updated_at', 'deleted_at', 'salt', 'password']))
    	<td>
    		@if (!empty($column->json))
        		@if (!empty($column->json['table']))
					{{$doubleQ}} getTableValue('{{$column->json['table']}}', $record->{{ $column->name }}, '{{$column->json['id']}}', '{{$column->json['name']}}')}}
            	@else
    				@foreach($column->json as $value => $label)
    				 {{$at}}if ($record->{{$column->name}} == '{{$value}}') {{$label}} {{$at}}endif
    				@endforeach
            	@endif
        	@else
        	   {{$doubleQ}} $record->{{ $column->name }} }}
        	@endif
    	 </td>
        @endif
    @endforeach
	     	<td>
				<a href="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}}$record->{{$primaryKey}} }}">查看</a>
				<a data-url="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}}$record->{{$primaryKey}} }}/delete"  data-pk="{{$primaryKey}}" data-value="{{$doubleQ}}$record->{{$primaryKey}}}}" onclick="delete_item(this)"  style="cursor: pointer">删除</a>
				<a  href="/{{$adminPath}}/{{$routeName}}/{{$doubleQ}}$record->{{$primaryKey}} }}/edit">编辑</a>
			</td>
	     	</tr>
    {{$at}}endforeach
	  </table>
	</div>
	<div class="float-right">
		<nav aria-label="Page navigation example">
			{{$doubleQ}} $records->appends([ 'search' => $search,
            'search_field' => $searchField,
            'order_by' => $orderBy])->links('pagination') }}
		</nav>
	</div>
</div>

{{$at}}endsection
﻿
