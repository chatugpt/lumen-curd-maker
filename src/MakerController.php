<?php

namespace Le2le\Maker;

use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MakerController extends Controller
{
    private $framework = '';

    public function __construct()
    {
        $framework = 'lumen';
        if (app() instanceof \Illuminate\Foundation\Application) {
            $framework = 'laravel';
        }
        $this->framework = $framework;
    }

    public function index(Request $request, $table = '')
    {
        $getTables = $request->input('table', $table);
        $model = $request->input('model', 0);
        $controller = $request->input('controller', 0);
        $admincontroller  = $request->input('admincontroller', 0);
        $view = $request->input('view', 0);
        $validation = $request->input('validation', 0);
        $overwrite = $request->input('overwrite', 0);
		$soft_delete = $request->input('soft_delete', 0);
		$timestamps = $request->input('timestamps', 0);
		$model_namespace = $request->input('model_namespace', 0);

        if(empty($getTables))
        {
            $dbName = app()->db->getDatabaseName();
            $tableName = 'Tables_in_' . $dbName;
            $tables = app()->db->select('SHOW TABLES');

            echo '<style>body{zoom:2;align:center;}</style>';
            echo '<form method="post" /><select multiple="multiple" name="table[]" size="'.count($tables).'" style="width:200px;">';

            foreach ($tables AS $table)
            {
				$tablePrefix =  env('DB_PREFIX', '');
				$count = 1;
				$tableOne = str_replace($tablePrefix, '', $table->$tableName, $count);

                echo '<option value ="'. $table->$tableName .'">'. $tableOne .'</option>';
                //echo $table;
            }
            echo '</select><br /><br />';

            echo '<input name="model" type="checkbox" value="1" />model';
            echo '<input name="controller" type="checkbox" value="1" />controller';
			echo '<input name="soft_delete" type="checkbox" value="1" />soft_delete';
			echo '<input name="timestamps" type="checkbox" value="1" />timestamps';
            echo '<input name="admincontroller" type="checkbox" value="1" />admin controller';
            echo '<input name="view" type="checkbox" value="1" />view<br /><br />';
			echo 'model命名空间：<input name="model_namespace" type="input" value="App\Models" /><br /><br />';
            echo '<input name="overwrite" type="checkbox" value="1" />替换掉已存在的文件<br /><br />';
            echo '<button type="submit" name="submit" value="submit">submit</button></form>';
            return;
        }


        $routeName = $routeInfo = '';

        $adminPath = config('config.adminPath') ;

        $adminPath = !empty($adminPath) ? $adminPath : 'admin';
        foreach ($getTables as $table)
        {


            $columns = $this->getAllColForTable($table);

            if(empty($columns))
            {
                echo 'error table';
                return;
            }

			$tablePrefix =  env('DB_PREFIX', '');
			$count = 1;
			$table = str_replace($tablePrefix, '', $table, $count);
            $routeName = $table;
            $t = explode('_', $table);
            $controllerName = '';
            foreach ($t as $v)
            {
                $controllerName .= ucfirst($v);
            }


            $primaryKey = '';
            foreach($columns as $item){
                if( $item->prikey == 'PRI' ) {
                    $primaryKey = $item->name;
                    break;
                }

            }


            $tableData = [
                'columns' => $columns,
                'table' => $table,
                'controllerName' => $controllerName,
                'routeName' => $routeName,
                'phpTag' => '<?php' ,
				'timestamps' => $timestamps,
                'primaryKey'=>$primaryKey,
                'at' => '@',
                'doubleQ' => '{{',
                'adminPath' => $adminPath,
				'soft_delete' => $soft_delete,
				'model_namespace' => $model_namespace,
                'framework' => $this->framework
            ];

            if(!empty($controller))
            {

                $content = view('maker::make.apicontroller', $tableData)->render();
                $file = app()->basePath('app'.DIRECTORY_SEPARATOR .'Http'.DIRECTORY_SEPARATOR.'Controllers'). DIRECTORY_SEPARATOR. $controllerName . 'Controller.php';

                if(!file_exists($file) || (file_exists($file) && $overwrite))
                {
                    file_put_contents($file, $content);
                }
            }

            if(!empty($admincontroller))
            {
                $content = view('maker::make.controller', $tableData)->render();

                $fileDir = app()->basePath('app'.DIRECTORY_SEPARATOR .'Http'.DIRECTORY_SEPARATOR.'Controllers'). DIRECTORY_SEPARATOR . 'Admin';

                if(!is_dir($fileDir))
                {
                    mkdir($fileDir);
                }

                $file = $fileDir . DIRECTORY_SEPARATOR. $controllerName . 'Controller.php';

                if(!file_exists($file) || (file_exists($file) && $overwrite))
                {
                    file_put_contents($file, $content);
                }
            }

            if(!empty($model))
            {
				$fillable = [];
                foreach($columns as $item){
                    if(!in_array($item->name, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    {
                        $fillable[] = '"' .$item->name.'"';
                    }

                }
                $tableData['fillable'] = implode(',', $fillable);
				$tableData['rules'] =  $this->getValidationRules($columns);
                $content = view('maker::make.model', $tableData)->render();

                $fileDir = app()->basePath('app'.DIRECTORY_SEPARATOR .'Models');

                if(!is_dir($fileDir))
                {
                    mkdir($fileDir);
                }


                $file = $fileDir . DIRECTORY_SEPARATOR. $controllerName . '.php';

                if(!file_exists($file) || (file_exists($file) && $overwrite))
                {
                    file_put_contents($file, $content);
                }

                if($model_namespace == 'form')
                {
                    file_put_contents('D:\xampp\htdocs\en.okeysc\system\form\\' .  $controllerName . '.php', $content);
                }
            }

            if(!empty($view))
            {
                $views = ['index', 'show', 'edit'];
                foreach ($views AS $one)
                {
                    $content = view('maker::make.' . $one, $tableData)->render();
                    $viewDir = app()->basePath('resources'.DIRECTORY_SEPARATOR .'views') . DIRECTORY_SEPARATOR . $table;
                    if(!is_dir($viewDir))
                    {
                        mkdir($viewDir);
                    }

                    $file = $viewDir . DIRECTORY_SEPARATOR . $one . '.blade.php';

                    if(!file_exists($file) || (file_exists($file) && $overwrite))
                    {
                        file_put_contents($file, $content);
                    }
                }
            }


            $this->makeIdeHelper();


            $routeInfo .= $this->getRoute($controllerName, $table) . "\r\n";
        }

        echo '<textarea rows="20" cols="100">' . $routeInfo . '</textarea><br />';
        echo '把以上复制到routes/web.php中';
    }

    public function getRoute($controllerName, $table)
    {
        /**
         GET	/photos	index	photos.index
         GET	/photos/create	create	photos.create
         POST	/photos	store	photos.store
         GET	/photos/{photo}	show	photos.show
         GET	/photos/{photo}/edit	edit	photos.edit
         PUT/PATCH	/photos/{photo}	update	photos.update
         DELETE	/photos/{photo}	destroy	photos.destroy
         */

        $routeName = $table;

        $prefix = $this->framework == 'lumen' ? '$router->':'Route::';

        $str = '';
        $str .= "{$prefix}get('$routeName', '{$controllerName}Controller@index');\r\n";
        $str .= "{$prefix}get('$routeName/create', '{$controllerName}Controller@edit');\r\n";
        $str .= "{$prefix}get('$routeName/{id}/edit', '{$controllerName}Controller@edit');\r\n";
        $str .= "{$prefix}get('$routeName/{id}', '{$controllerName}Controller@show');\r\n";
        $str .= "{$prefix}post('$routeName/{id}/delete', '{$controllerName}Controller@destroy');\r\n";
        $str .= "{$prefix}post('$routeName/{id}', '{$controllerName}Controller@store');\r\n";
        $str .= "{$prefix}post('$routeName', '{$controllerName}Controller@store');\r\n";


        $adminPath = config('config.adminPath');

        if(empty($adminPath))
        {
            $adminPath = 'admin';
        }


        $adminNameSpace = 'Admin';

        $strAdmin = "\$router->group(['namespace' => 'Admin', 'prefix'=> '$adminPath'], function() use (\$router){\n";
        foreach (explode("\r\n", $str) as $line)
        {
            $strAdmin .= "\t" . $line ."\r\n";
        }

        $strAdmin .= '});';
        return $str ."\r\n". $strAdmin;
    }


    public function makeIdeHelper()
    {
        return;
        $this->aliases = [
            'Illuminate\Contracts\Foundation\Application' => 'app',
            'Illuminate\Contracts\Auth\Factory' => 'auth',
            'Illuminate\Contracts\Auth\Guard' => 'auth.driver',
            'Illuminate\Contracts\Cache\Factory' => 'cache',
            'Illuminate\Contracts\Cache\Repository' => 'cache.store',
            'Illuminate\Contracts\Config\Repository' => 'config',
            'Illuminate\Container\Container' => 'app',
            'Illuminate\Contracts\Container\Container' => 'app',
            'Illuminate\Database\ConnectionResolverInterface' => 'db',
            'Illuminate\Database\DatabaseManager' => 'db',
            'Illuminate\Contracts\Encryption\Encrypter' => 'encrypter',
            'Illuminate\Contracts\Events\Dispatcher' => 'events',
            'Illuminate\Contracts\Hashing\Hasher' => 'hash',
            'Psr\Log\LoggerInterface' => 'log',
            'Illuminate\Contracts\Queue\Factory' => 'queue',
            'Illuminate\Contracts\Queue\Queue' => 'queue.connection',
            'Illuminate\Http\Request' => 'request',
            'Laravel\Lumen\Routing\UrlGenerator' => 'url',
            'Illuminate\Contracts\Validation\Factory' => 'validator',
            'Illuminate\Contracts\View\Factory' => 'view',
        ];

        app()->validator;
        $binds = app()->getBindings();

        $bindNames = array_keys($binds);
        $aliases = array_values($this->aliases);
        $bindNames = array_merge($bindNames, $aliases);

        $bindNames = array_unique($bindNames);
        $uses = $vars = '';
        foreach ($bindNames AS $one)
        {
            if(preg_match('/[\\\|\.]/', $one) != false )
            {
                continue;
            }

            if($one == 'encrypter')
            {
                continue;
            }

            $c = app($one);

            $class = new ReflectionClass($c); // 建立 Person这个类的反射类

            $namespaceName = $class->getNamespaceName();
            $name = $class->getName();



            $uses .= "use $name;\r\n";

            $vars .= "\t/**\r\n"
                ."\t* @var \\$name \r\n"
                ."\t*/\r\n"
                    ."\tpublic \$$one;\r\n\r\n";


        }

        $fileContent ='<?php'. "\r\n" . "namespace Laravel\Lumen;"
            ."\r\n" . "use Illuminate\Container\Container;"
                ."\r\n".$uses."\r\n"
                    . "\r\n".'class Application extends Container{' . "\r\n".
                    $vars . "\r\n"
                        . "\r\n".'}';

                        file_put_contents(base_path() . '/ideHelper.php', $fileContent);

    }

    /**
     ["name"]
     ["type"]
     ["remark"]
     ["column_type"]
     */
    private function getAllColForTable($table)
    {

        $databaseName = app()->db->connection()->getDatabaseName();
        $sql = "SELECT COLUMN_NAME AS 'name',
                DATA_TYPE AS 'type',
                COLUMN_COMMENT AS 'remark' ,
                COLUMN_KEY as 'prikey',
                COLUMN_TYPE as 'column_type',
                COLUMN_DEFAULT as 'default',
                IS_NULLABLE as 'is_null'
                FROM information_schema.`COLUMNS`
                WHERE TABLE_SCHEMA = '$databaseName' AND TABLE_NAME = '$table';";

        $tables = app()->db->select($sql);

        foreach ( $tables as $item ) {
            preg_match('/{.*}/' , $item->remark ,$match);
            $options = [];
            if(isset($match) && isset($match[0])) {
                $RemarkJson =  json_decode($match[0] ,1);
                $item->json = $RemarkJson;
                $item->remark = str_replace($match[0] ,'' , $item->remark);
            }

        }

        return $tables;
    }



    function getValidationColumns($columns)
    {
        /**
         TEXT 65,535 bytes ~64kb
         MEDIUMTEXT 16,777,215 bytes ~16Mb
         LONGTEXT 4,294,967,295 bytes ~4Gb
         */



        $rule = [
            'tinyint\((\d+)\) unsigned' => 'integer|min:0|max:255',
            'tinyint\((\d+)\)' => 'integer|min:-128|max:127',
            'int\((\d+)\) unsigned'=>'integer|min:0|max:4294967295',
            'int\((\d+)\)' => 'integer|min:-2147483648|max:2147483647',
            'datetime'=> 'date',
            'date'=> 'date|date_format:Y-m-d',
            'varchar\((\d+)\)' => 'max:$1',
            'char\((\d+)\)' => 'max:$1'
        ];

        $validArray = [];
        foreach ($columns as & $column)
        {
            $validRule = [];
            if(in_array($column->name, ['id', 'created_at', 'updated_at', 'deleted_at']))
            {
                continue;
            }

            if(strtoupper($column->is_null) == 'NO' && ($column->default === '' || $column->default === NULL )  && $column->prikey != 'PRI')
            {
                $validRule[] = 'present';
            }

            $type  = $column->column_type;
            foreach ($rule as $key => $value)
            {
                if(preg_match('/' . $key . '/is', $type, $matches))
                {
                    $validRule[]  = preg_replace('/' . $key . '/is', $value, $type);
                    break;
                }
            }


            if(preg_match('/decimal\((\d+),(\d+)\)/is', $type, $matches))
            {
                $max = str_repeat(9, $matches[1]) . '.' . str_repeat(9, $matches[2]);
                if(strstr($type, 'unsigned') !== false)
                {
                    $min = 0;
                }
                else
                {
                    $min = '-'.str_repeat(9, $matches[1]) . '.' . str_repeat(9, $matches[2]);
                }

                $validRule[]  = 'numeric|min:'.$min.'|max:'.$max.'';
            }

            $column->validRule = implode('|', $validRule);

        }

        return $columns;

    }

	function getValidationRules($columns)
	{
		/**
		TEXT 65,535 bytes ~64kb
		MEDIUMTEXT 16,777,215 bytes ~16Mb
		LONGTEXT 4,294,967,295 bytes ~4Gb
		 */



		$rule = [
			'smallint\((\d+)\) unsigned' => 'integer|min:0|max:65535',
			'smallint\((\d+)\)' => 'integer|min:-32768|max:32767',
			'tinyint\((\d+)\) unsigned' => 'integer|min:0|max:255',
			'tinyint\((\d+)\)' => 'integer|min:-128|max:127',
			'int\((\d+)\) unsigned'=>'integer|min:0|max:4294967295',
			'int\((\d+)\)' => 'integer|min:-2147483648|max:2147483647',
			'datetime'=> 'date',
			'date'=> 'date|date_format:Y-m-d',
			'varchar\((\d+)\)' => 'max:$1',
			'char\((\d+)\)' => 'max:$1'
		];

		$validArray = [];
		foreach ($columns as & $column)
		{
			$validRule = [];
			if(in_array($column->name, ['id', 'created_at', 'updated_at', 'deleted_at']))
			{
				continue;
			}

			if(strtoupper($column->is_null) == 'NO' && ($column->default === '' || $column->default === NULL )  && $column->prikey != 'PRI')
			{
				$validRule[] = 'present';
			}

			$type  = $column->column_type;
			foreach ($rule as $key => $value)
			{
				if(preg_match('/' . $key . '/is', $type, $matches))
				{
					$validRule[]  = preg_replace('/' . $key . '/is', $value, $type);
					break;
				}
			}


			if(preg_match('/decimal\((\d+),(\d+)\)/is', $type, $matches))
			{
				$max = str_repeat(9, $matches[1]) . '.' . str_repeat(9, $matches[2]);
				if(strstr($type, 'unsigned') !== false)
				{
					$min = 0;
				}
				else
				{
					$min = '-'.str_repeat(9, $matches[1]) . '.' . str_repeat(9, $matches[2]);
				}

				$validRule[]  = 'numeric|min:'.$min.'|max:'.$max.'';
			}

			$validArray[$column->name] = implode('|', $validRule);
		}

		return $validArray;

	}

}
