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
		'event' => '事件設定',
		'global' => '全站設定'
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
				$ret = new PiaAudit();
				$ret->event_id = PiaGlobal::get_current_event();
				return $ret;
				break;
			case 'event':
				if(!is_null($id))  return PiaEvent::find($id);
				return new PiaEvent();
				break;
			case 'global':
				if(!is_null($id))  return PiaGlobal::find($id);
				return new PiaGlobal();
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

		return View::make('admin/info')->with([
				'type' => $type,
				'title' => $this->type2name[$type],
				'columns' => $columns,
				'info' => $info,
				'obj' => $obj,
			 ]);
	}

	public function cal(){
		$type = 'cal';
		$obj = new PiaAudit();
		$info = $obj->info_table(array('ad_time_end'));
		return View::make('admin/cal')->with(array(
			'type' => 'cal',
			"info" => $info,
			'title' => $this->type2name[$type],
		));
	}

	public function edit($type,$id = null)
	{
		$obj = $this->type2instancd($type,$id);
		$form_fields = $obj->form_fields;
		$obj->p_pass = "";

		if($type == "audit" && !isset($id))
			return View::make("admin/audit");
		return View::make('admin/edit')->with([
			'type' => $type,
			'id' => $id,
			'title' => $this->type2name[$type],
			'fields' => $form_fields,
			 'obj' => $obj
			 ]);
	}

	public function edit_process($type,$id = null)
	{
		$obj = $this->type2instancd($type,$id);
		try {
			$obj->fill_field(Input::all())->save();
		} catch (Exception $e) {
			return Response::make($e->getMessage())->header('Refresh', "3;url=" . route('admin_info',[$type,$id]));
		}
		Session::set("message","設定成功!");
		return Response::json(["redirect" => route("admin_info",$type)]);
	}

	public function add_autitor(){
		$input = Input::all();
		$type = 'audit';
		try {
			$count = 0;
			$err = 0;
			$auditor_OK = [];
			foreach ($input['ad_dept_id'] as $key => $value) {
				try{
					$auditor = new PiaAudit();
					$auditor->event_id = $input['event_id'];
					$auditor->p_id = $input['p_id'];
					$auditor->ad_dept_id = $input['ad_dept_id'][$key];
					$auditor->ad_time_from = $input['ad_time_from'][$key];
					$auditor->ad_time_end = $input['ad_time_end'][$key];
					$auditor->save();
					$auditor_OK[] = $auditor;
					++$count;
				}catch(Exception $e){
					$err++;
				}
			}
			if($err)
				throw new Exception("共有 $err 筆錯誤，請檢查！");
			Session::set("message","共".$count."筆資料設定成功!");
		} catch (Exception $e) {
			foreach ($auditor_OK as $key => $auditor) { // rollback...
				$auditor->delete();
			}
			return Response::make($e->getMessage())->header('Refresh', "3;url=" . route('admin_info',[$type]));
		}
		return Response::json(["redirect" => route("admin_info",$type)]);
	}

	public function del($type,$id)
	{
		try{
			$obj = $this->type2instancd($type);
			$obj = $obj->find($id);
			$obj->delete();
			Session::set("message","刪除成功!");
			return Redirect::route('admin_info',$type);
		} catch (Exception $e) {
			Session::set("message",$e->getMessage());
			return Redirect::route('admin_info',$type);
		}
	}

	public function reports(){
		$reports = PiaReport::all();
		return View::make('admin/reports')->with(
			array(
				'reports' => $reports,
				'title' => '回報狀態',
				));
	}

	public function view_report($id){
		$report = PiaReport::findOrFail($id);
		return View::make('preview')->with(
		array(
			'content' => $report->gen_html(),
			"download_url" => route('admin_download_report', $id),
			'title' => '稽核報告預覽：' . $report->r_serial,
		));
	}

	public function download_report($id){
		$report = PiaReport::findOrFail($id);
		return Response::download($report->get_paper(), "$report->r_serial 稽核報告.pdf");
	}

	public function report_item($id){
		$item = PiaReportItem::findOrFail($id);
		return View::make('preview')->with(
		array(
			'content' => $item->gen_html(),
			"download_url" => route('admin_download_report_item',$id),
			'title' => '校正預防報告預覽：',
		));
	}

	public function download_report_item($id){
		$item = PiaReportItem::findOrFail($id);
		return Response::download($item->get_paper(), $item->get_serial() . " 矯正預防報告.pdf");
	}
}
