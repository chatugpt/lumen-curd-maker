{!! $phpTag !!}
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {{$controllerName}} extends Model
{
    use SoftDeletes;
    
    protected $table = '{{$table}}';
    
    protected $fillable = [{!! $fillable !!}];
    
}