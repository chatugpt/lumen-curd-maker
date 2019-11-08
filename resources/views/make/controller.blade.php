{!! $phpTag !!}

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Models\{{$controllerName}};

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
        
        return view('{{$routeName}}.index', [
            'records' => $records,
            'search' => $search,
            'searchField' => $searchField,
            'orderBy' => $orderBy,
            'sort' => $sort
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
        $model->save();
        return redirect('{{$adminPath}}/{{$routeName}}');
        
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
