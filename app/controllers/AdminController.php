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

	private function type2instancd($type)
	{
		switch ($type) {
			case 'person':
				return new PiaPerson();
				break;
			case 'dept':
				return new PiaDept();
				break;
			case 'audit':
				return new PiaAudit();
				break;
			case 'event':
				return new PiaEvent();
				break;

			default:
				App::abort(404);
		}
	}

	public function info($type)
	{
		$obj = $this->type2instancd($type);

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
		$obj = $this->type2instancd($type);

		if(!is_null($id))
			$obj = $obj->find($id);
		$form_fields = $obj->form_fields;

		$obj->p_pass = "";

		return View::make('admin/edit')->with(array('type' => $type,'title' => $this->type2name[$type],'fields' => $form_fields, 'obj' => $obj));
	}

	public function edit_process($type,$id = null)
	{
		$obj = $this->type2instancd($type);

		if(!is_null($id))
			$obj = $obj->find($id);
		try {
			$obj->fill_field(Input::all())->save();
		} catch (Exception $e) {
			Session::set("message","設定失敗！請確認您的輸入！");
			return Redirect::route('admin_edit',$type);
		}
		Session::set("message","設定成功！");
		return Redirect::route('admin_info',$type);
	}

	public function del($type,$id)
	{
		$obj = $this->type2instancd($type);
		$obj = $obj->find($id);
		$obj->delete();
		Session::set("message","刪除成功！");
		return Redirect::route('admin_info',$type);
	}

}
