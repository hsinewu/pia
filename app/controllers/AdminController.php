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
		'dept' => '單位資料表',
		'audit' => '稽核設定',
		'cal' => '行事曆',
		'event' => '事件設定'
	);

	public function info($type)
	{
		switch ($type) {
			case 'person':
				$obj = new PiaPerson();
				break;
			case 'dept':
				$obj = new PiaDept();
				break;
			case 'audit':
				$obj = new PiaAudit();
				break;
			case 'event':
				$obj = new PiaEvent();
				break;

			default:
				App::abort(404);
		}
		
		$info = $obj->info_table();
		$columns = $obj->info_table_columns;

		// var_dump($info[0]->{$obj->getPK()});
		// die();

		return View::make('admin/info')->with(array('type' => $type,'title' => $this->type2name[$type],'columns' => $columns, "info" => $info, 'obj' => $obj));
	}

	public function cal(){
		$type = 'cal';
		return View::make('admin/cal')->with(array(
			'type' => 'cal',
			'title' => $this->type2name[$type],
		));
	}

	public function edit($type,$id = null)
	{
		switch ($type) {
			case 'person':
				$obj = new PiaPerson();
				break;
			case 'dept':
				$obj = new PiaDept();
				break;
			case 'audit':
				$obj = new PiaAudit();
				break;
			case 'event':
				$obj = new PiaEvent();
				break;

			default:
				App::abort(404);
		}

		if(!is_null($id))
			$obj = $obj->find($id);
		$form_fields = $obj->form_fields;

		// var_dump((new PiaDept())->gen_key_value());
		// die();

		// return View::make('macro/select')->with(array(
		// 	'type' => "dept",
		// 	'name' => "123",
		// 	'value' => "",
		// ));

		return View::make('admin/edit')->with(array('title' => $this->type2name[$type],'fields' => $form_fields, 'obj' => $obj));
	}

}
