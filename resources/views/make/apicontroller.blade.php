{!! $phpTag !!}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{{$controllerName}};
use App\Validate\{{$controllerName}} as Validate;

class {{$controllerName}}Controller extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchField = $request->input('search_field');
        $orderBy = $request->input('order_by', '{{$primaryKey}}_desc');
        $orderByArray = explode("_", $orderBy);
        $temp = array_pop($orderByArray);
        $sort[0] = implode("_", $orderByArray);
        $sort[1] = $temp;
        $model = new {{$controllerName}}();
        if (! empty($search) && ! empty($searchField)) {
            $model = $model->where($searchField, $search);
        }
        
        $records = $model->orderBy($sort[0], $sort[1])->paginate(15);
        
        return response()->json(['status' => 0, 'data' => $records]);
    }

    public function show(Request $request, $id)
    {
        $id = intval($id);
        if (!$id) {
           return response()->json(['status' => 1, 'data' => 'id error']);
        }
        $model = new {{$controllerName}}();
        $model = $model->find($id);
        return response()->json(['status' => 0, 'data' => $model]);
    }

    public function store(Request $request, $id = null)
    {
        $model = !empty($id) ? {{$controllerName}}::find($id) : new {{$controllerName}}();
        
        $validate = new Validate();
        $validate = (array)$validate;
        if(!empty($model->id))
        {
            foreach ($validate as $key => $value)
            {
                if(!$request->has($key))
                {
                    unset($validate[$key]);
                }
            }
        }
        
        $validator = app()->validator->make($request->all(), $validate);
        
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => $validator->errors()->getMessages()]);
        }
        
        $fillable = $model->getFillable();
        foreach ($fillable as $key) {
            if($request->has($key))
            {
                $model->{$key} = $request->input($key);
            }
        }
        $model->save();
        return response()->json(['status' => 0, 'data' => $model]);
        
    }

    
    public function destroy(Request $request, $id)
    {
        $id = intval($id);
        if (!$id) {
           return response()->json(['status' => 1, 'data' => 'id error']);
        }
        
        $model = new {{$controllerName}}();
        $find = $model->find($id);
        if(!empty($find))
        {
        	$find->delete();
        }

        return response()->json(['status' => 0, 'data' => []]);
    }
}
