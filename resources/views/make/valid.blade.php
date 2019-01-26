{!! $phpTag !!}

namespace App\Validate;

class {{$controllerName}} 
{
@foreach( $columns as $column )
    /**      
     * 字段: {{$column->name}}
     * 主键: @if ($column->prikey == 'PRI') 1 @else 0 @endif
     
     * 可空: {{$column->is_null}}
     * 说明：{{$column->remark}}
     * 类型：{{$column->column_type}}
     */
    public ${{$column->name}} = '@isset($column->validRule){{$column->validRule}}@endisset';
    
@endforeach
}
