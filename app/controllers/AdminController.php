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

	private function type2instancd($type,$id = null)
	{
		switch ($type) {
			case 'person':
				if(!is_null($id))  return PiaPerson::find($id);
				return new PiaPerson();
				break;
			case 'dept':
				if(!is_null($id))  return PiaDept::find($id);
				return new PiaDept();
				break;
			case 'audit':
				if(!is_null($id))  return PiaAudit::find($id);
				return new PiaAudit();
				break;
			case 'event':
				if(!is_null($id))  return PiaEvent::find($id);
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

		// var_dump($info);
		// var_dump(DB::getQueryLog());
		// die();

		return View::make('admin/info')->with(array('type' => $type,'title' => $this->type2name[$type],'columns' => $columns, "info" => $info, 'obj' => $obj));
	}

	public function cal(){
		$type = 'cal';
		$obj = new PiaAudit();
		$info = $obj->info_table(array('ad_time_end'));
		// var_dump($info);
		// die();
		return View::make('admin/cal')->with(array(
			'type' => 'cal',
			"info" => $info,
			'title' => $this->type2name[$type],
		));
	}

	public function edit($type,$id = null)
	{
		$obj = $this->type2instancd($type,$id);

		// if(!is_null($id))
		// 	$obj = $obj->find($id);
		$form_fields = $obj->form_fields;

		$obj->p_pass = "";
		if($type=="audit" && $id==NULL)
			return View::make('admin/audit')->with(array('type' => $type,'id' => $id,'title' => $this->type2name[$type],'fields' => $form_fields, 'obj' => $obj));
		else
			return View::make('admin/edit')->with(array('type' => $type,'id' => $id,'title' => $this->type2name[$type],'fields' => $form_fields, 'obj' => $obj));
	}

	public function edit_process($type,$id = null)
	{
		$obj = $this->type2instancd($type,$id);
		//die(is_null($id));
		// if(!is_null($id))
		// 	$obj = $obj->find($id);
		try {
			$obj->fill_field(Input::all())->save();
		} catch (PDOException $e) {
			Session::set("message","來自DB的錯誤訊息:".$e->getMessage());
			return Redirect::route('admin_edit',$type);
		} catch (Exception $e) {
			//$m = "";
			//foreach($e->getMessage() as $k => $v) $m.=$v;
			die($e->getMessage());
			Session::set("message","設定失敗!請確認您的輸入:"/*.$m*/);
			return Redirect::route('admin_edit',$type);
		}
		Session::set("message","設定成功!");
		return Redirect::route('admin_info',$type);
	}

	public function add_autitor(){
		$input = Input::all();
		$type = 'audit';
		try {
			foreach ($input['ad_dept_id'] as $key => $value) {
				$auditor = new PiaAudit();
				$auditor->event_id = $input['event_id'];
				$auditor->p_id = $input['p_id'];
				$auditor->ad_dept_id = $input['ad_dept_id'][$key];
				$auditor->ad_time_from = $input['ad_time_from'][$key];
				$auditor->ad_time_end = $input['ad_time_end'][$key];
				$auditor->save();
			}
			Session::set("message","設定成功!");
			return Redirect::route('admin_info',$type);
		} catch (PDOException $e) {
			Session::set("message","來自DB的錯誤訊息:".$e->getMessage());
			return Redirect::route('admin_edit',$type);
		} catch (Exception $e) {
			Session::set("message","設定失敗!請確認您的輸入:"/*.$m*/);
			return Redirect::route('admin_edit',$type);
		}
	}

	public function del($type,$id)
	{
		$obj = $this->type2instancd($type);
		$obj = $obj->find($id);
		$obj->delete();
		Session::set("message","刪除成功!");
		return Redirect::route('admin_info',$type);
	}

}
