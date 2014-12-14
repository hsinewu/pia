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
		$audit = PiaAudit::find($id);
		$report = $audit->new_report();

		// var_dump($report->r_time);
		// die();

		$result = array(
			"audit" => $audit,
			"report" => $report,
		);
		return View::make('audit/report')->with($result);
	}

}
