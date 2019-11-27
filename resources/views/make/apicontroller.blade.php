{!! $phpTag !!}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{{$controllerName}};

class {{$controllerName}}Controller extends Controller
{

    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', '{{$primaryKey}}_desc');

        $model = new {{$controllerName}}();
        $fillable = $model->getFillable();
        $fillable[] = 'id';
        foreach ($fillable as $key) {
            if($request->has($key))
            {
                $model = $model->where($key, $request->input($key));
            }
        }

        $orderArray = explode('_', $orderBy);

        if(count($orderArray) == 2 && in_array($orderArray[0], $fillable) && in_array($orderArray[1], ['desc', 'asc'])){
            $model = $model->orderBy($orderArray[0],  $orderArray[1]);
        }

        $records = $model->simplePaginate(15);

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
        $fillable = $model->getFillable();
        foreach ($fillable as $key) {
            if($request->has($key))
            {
                $model->{$key} = $request->input($key);
            }
        }

        $validator = app()->validator->make($model->toArray(), $model->rules);

        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' =>   $validator->getMessageBag()->getMessages()]);
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
