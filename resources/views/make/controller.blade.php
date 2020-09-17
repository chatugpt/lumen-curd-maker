{!! $phpTag !!}

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Models\{{$controllerName}};

class {{$controllerName}}Controller extends Controller
{

    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', '{{$primaryKey}}_desc');

        $searchField =  $request->input('search_field');
        $search =  $request->input('search');

        $model = new {{$controllerName}}();
        $fillable = $model->getFillable();
        $fillable[] = '{{$primaryKey}}';

        foreach ($fillable as $key) {
            if($request->has($key))
            {
                $model = $model->where($key, $request->input($key));
            }
        }

        if($searchField && strlen($search) > 0 && in_array($searchField, $fillable))
        {
                $model = $model->where($searchField, $search);
        }

        $orderArray = explode('_', $orderBy);

        if(count($orderArray) == 2 && in_array($orderArray[0], $fillable) && in_array($orderArray[1], ['desc', 'asc'])){
            $model = $model->orderBy($orderArray[0],  $orderArray[1]);
        }

        $records = $model->paginate(15);

        return view('{{$routeName}}.index', [
            'records' => $records,
            'search' => $search,
            'searchField' => $searchField,
            'orderBy' => $orderBy,
            'sort' => $orderArray
        ]);
    }

    public function show(Request $request, $id)
    {
        $id = intval($id);
        if (! $id) {
        	echo 'error id';
            return;
        }
        $model = new {{$controllerName}}();
        $model = $model->find($id);
        return view('{{$routeName}}.show', ['data' => $model]);
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
         return response()->json(['status' => 0, 'data' =>  $model]);

    }

    public function edit(Request $request, $id = null)
    {
        $id = intval($id);

        $model = new {{$controllerName}}();
        $model = $model->find($id);
		$model = !empty($model->id) ? $model : new {{$controllerName}}();

        return view('{{$routeName}}.edit', ['data' => $model]);
    }


    public function destroy(Request $request, $id)
    {
        $id = intval($id);
        if (!$id) {
            return;
        }

        $model = new {{$controllerName}}();
        $find = $model->find($id);
        $find->delete();
        return ['status' => 0];
    }
}
