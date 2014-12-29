<?php

class AuditController extends Controller {

	public function tasks()
	{
		$user = Session::get('user');

		$columns = array(
			"a_id" => "#",
			// "p_name" => "稽核人",
			"ad_time_from" => "開始時間",
			"ad_time_end" => "結束時間",
			"dept_name" => "受稽單位"
		);
		$info = $user->audit()->get(array_keys($columns));

		return View::make('audit/tasks')->with(array('title' => "稽核任務列表",'columns' => $columns, "info" => $info));
	}

	public function report($id)
	{
		try {
			$audit = PiaAudit::find($id);
			$report = $audit->new_report();
			$obj=$report->item();

			$result = array(
				"audit" => $audit,
				'report' => $report,
				'obj'=>$obj,
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
		//var_dump($input);
		//die();
		try {
			$audit = PiaAudit::find($id);
			$report = $audit->new_report();
			$report->r_serial = Input::get('r_serial');
			$report->r_time = Input::get('r_time');
			$report->r_msg = Input::get('r_msg');
			if(Input::get('status')==0) $report->status = '暫存';
			else $report->status = '儲存';
			$report->save();
			$count = 0;
			foreach ($input['ri_base'] as $key => $value) {
				if( $input['ri_base'][$key]=="" || $input['ri_discover'][$key]=="" || $input['ri_recommand'][$key]=="" )
						continue;
				$item = $report->new_item();
				$item->ri_base = $input['ri_base'][$key];
				$item->ri_discover = $input['ri_discover'][$key];
				$item->ri_recommand = $input['ri_recommand'][$key];
				$item->save();
				++$count;
			}
			Session::set("message","共".$count."筆發現回報成功!");
			return Redirect::route('audit_tasks');
		} catch (PDOException $e) {
			Session::set("message","來自DB的錯誤訊息:".$e->getMessage());
			return Redirect::route('audit_report',$id);
		} catch (Exception $e) {
			$report->delete();
			Session::set("message","設定失敗!請確認您的輸入:...");
			return Redirect::route('audit_report',$id);
		}
	}

}
