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

			$count=0;
			foreach ($input['ri_base'] as $key => $value){
				$items[ $key ] = [
					// 'ri_id' => isset($input['ri_id'][$key]) ? $input['ri_id'][$key] : null ,
					'ri_base' => $input['ri_base'][$key],
					'ri_discover' => $input['ri_discover'][$key],
					'ri_recommand' => $input['ri_recommand'][$key],
				];
				if(!isset($input['ri_id'][$key])){
					$item = new PiaReportItem($items[ $key ]);
					$report->items()->save($item);
				}
				else{
					$item = PiaReportItem::find($input['ri_id'][$key]);
					$item->update($items[ $key ]);
				}
				++$count;
			}
			//echo '<pre>'.var_export($items, true).'</pre>';
			//die();
			/*$count = 0;
			$items = $report->items();
			$items->delete();
			foreach ($input['ri_base'] as $key => $value) {
				if( $input['ri_base'][$key]=="" || $input['ri_discover'][$key]=="" || $input['ri_recommand'][$key]=="" )
						continue;
				//if() $item = $report->items()::find($input['ri_id'][$key]);
				//else
					$item = $report->new_item();
				$item->ri_base = $input['ri_base'][$key];
				$item->ri_discover = $input['ri_discover'][$key];
				$item->ri_recommand = $input['ri_recommand'][$key];
				$item->save();
				++$count;
			}*/


			if($report->is_saved()){
				$report->send_email();
				Session::set("message","共".$count."筆發現已儲存，並且發信通知成功!");
			}
			else{
				Session::set("message","共".$count."筆發現暫存成功!");
			}

			return Redirect::route('audit_tasks');
		} catch (PDOException $e) {
			Session::set("message","來自DB的錯誤訊息:".$e->getMessage());
			return Redirect::route('audit_report',$id);
		} catch (Exception $e) {
			//$report->delete();
			Session::set("message","設定失敗!請確認您的輸入:...".$e->getMessage());
			return Redirect::route('audit_report',$id);
		}
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
		    if($es->es_used)
		        throw new Exception("已經確認過了！", 1);

		    $es->es_used = true;
		    $es->save();

		    $report = $es->report()->firstOrFail();
		    if(!$report->next_level()){
		    	$report->send_email();
		    	Session::set("message","確認成功，並成功通知下一位主管！");
		    }
		    else
		    	Session::set("message","確認成功！");
		} catch (Exception $e) {
		    Session::set("message",$e->getMessage());
		}
		return Redirect::to('/');
	}

	public function view_report($id){
		$report = PiaReport::findOrFail($id);
		return View::make('report')->with(
		array(
			'report' => $report,
			'items' => $report->items()->get(),
			'title' => '稽核報告預覽：' . $report->r_serial,
		));
	}

	public function download_report($id){
		$report = PiaReport::findOrFail($id);
		return Response::download($report->gen_paper(), $report->r_serial);
	}
}
