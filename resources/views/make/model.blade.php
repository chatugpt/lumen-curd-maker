{!! $phpTag !!}
namespace {{$model_namespace}};

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
@foreach( $columns as $column )
* @property string ${{$column->name}} {{$column->remark}}
@endforeach
*/
class {{$controllerName}} extends Model
{
@if ($soft_delete)
    use SoftDeletes;
@endif
    protected $table = '{{$table}}';
@if (!$timestamps)
    public $timestamps = false;
@endif
@if ($primaryKey != 'id')
    public $primaryKey = '{{$primaryKey}}';
@endif
    public $fillable = [{!! $fillable !!}];

@if($rules)
    public $rules = [
@foreach( $rules as $field => $rule)
        '{{$field}}' => '{{$rule}}',
@endforeach
    ];
@endif

}
