<?php

class AuditController extends Controller {

	public function tasks()
	{
		$user = Session::get('user');

		$columns = array(
			"a_id" => "#",
			// "p_name" => "稽核人",
			"event_id" => "所屬事件",
			"ad_time_from" => "開始時間",
			"ad_time_end" => "結束時間",
			"dept_name" => "受稽單位"
		);
		$info = $user->audit()->get(array_keys($columns));

		return View::make('audit/tasks')->with([
				'title' => "稽核任務列表",
				'columns' => $columns,
				'info' => $info,
				'eventFilter' => true,
			]);
	}

	public function report($id)
	{
		try {
			$audit = PiaAudit::find($id);
			$report = $audit->new_report();
			$items=$report->items();

			$result = array(
				"audit" => $audit,
				'report' => $report,
				'items'=>$items,
			);
			return View::make('audit/report')->with($result);
		} catch (Exception $e) {
			Session::set("message",$e->getMessage());
			return Redirect::route('audit_tasks');
		}
	}

	public function report_process($id)
	{
		$input = Input::all();
		// var_dump($input);
		try {
			$audit = PiaAudit::find($id);
			$report = $audit->new_report();
			$report->r_serial = $input['r_serial'];
			$report->r_time = $input['r_time'];
			$report->r_msg = $input['r_msg'];

			if(!in_array(Input::get('status'), [0,1]))
				throw new Exception("Illegal status!");

			$report->set_state_level(Input::get('status'));
			$report->save();

			$item_err = [];
			foreach ($input['ri_base'] as $key => $value){
				$item_tmp = [];
				$item_tmp['ri_base'] = $input['ri_base'][$key];
				$item_tmp['ri_discover'] = $input['ri_discover'][$key];
				$item_tmp['ri_recommand'] = $input['ri_recommand'][$key];
				try{
					if(!isset($input['ri_id'][$key])){
						$item = new PiaReportItem($item_tmp);
						$report->items()->save($item);
					}
					else{
						$item = PiaReportItem::find($input['ri_id'][$key]);
						$item->update($item_tmp);
					}
				} catch (Exception $e) {
					$item_err[] = $e;
					continue;
				}
			}

			$item_err_cnt = count($item_err);
			$item_suc_cnt = count($input['ri_base']) - $item_err_cnt;
			if($item_err_cnt){
				$item_err_msg = "";
				foreach ($item_err as $key => $value) {
					$item_err_msg .= $value->getMessage() . ",\n";
				}
			}

			$report->gen_paper();

			if($report->is_saved()){
				if($item_err_cnt)
					throw new Exception("成功儲存 $item_suc_cnt 筆發現，但有 $item_err_cnt 筆發現有問題，將強制改以暫存方式儲存");
				$report->send_email();
				Session::set("message","共 $item_suc_cnt 筆發現已儲存，並且發信通知成功!");
			}
			else
				Session::set("message","共 $item_suc_cnt 筆發現暫存成功!");
		} catch (Exception $e) {
			if(isset($report)){
				$report->set_state_level(0);
				$report->save();
			}
			return Response::make($e->getMessage())->header('Refresh', "3;url=" . route('audit_report',$id));
		}
		return Response::json(["redirect" => route("audit_tasks")]);
	}

	public function calendar(){
		$audits = DB::table('auditor')
			->where('p_id', Session::get('user')->p_id)
			->join('dept','auditor.ad_dept_id','=','dept.dept_id')
			->get();
		return View::make('audit/calendar')->with(array(
			"audits" => $audits,
		));
	}

	public function sign($code)
	{
		try {
		    $es = PiaEmailSign::where('es_code', '=', $code)->firstOrFail();
			$report = $es->sign();
			$report->gen_paper();
			if(!$report->is_finished()){
				$report->send_email();
		    	Session::set("message","確認成功，並成功通知下一位主管！");
			}
			else
				Session::set("message","確認成功");
		} catch (Exception $e) {
		    Session::set("message",$e->getMessage());
		}
		return Redirect::to('/');
	}

	public function view_report($id){
		$report = PiaReport::findOrFail($id);
		return View::make('preview')->with(
		array(
			'content' => $report->gen_html(),
			"download_url" => route('audit_download_report', $id),
			'title' => '稽核報告預覽：' . $report->r_serial,
		));
	}

	public function download_report($id){
		$report = PiaReport::findOrFail($id);
		return Response::download($report->get_paper(), "$report->r_serial 稽核報告.pdf");
	}
}
