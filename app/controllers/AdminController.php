<?php

class AdminController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	private $type2name = array(
		'person' => '人員資料表',
		'dept' => '單位資料表'
	);

	public function info($type)
	{
		switch ($type) {
			case 'person':
				$obj = new Person();
				break;
			case 'dept':
				$obj = new Dept();
				break;
			default:
				App::abort(404);
		}
		
		$info = $obj->info_table();
		$columns = $obj->info_table_columns;

		return View::make('admin/info')->with(array('title' => $this->type2name[$type],'columns' => $columns, "info" => $info));
	}

}
