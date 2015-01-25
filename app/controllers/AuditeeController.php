<?php

class AuditeeController extends Controller {

	public function status(){
		return View::make('auditee/status')->with(
			array(
				'audits' => PiaAudit::where('ad_dept_id', Session::get('user')->dept_id)->get(),
				'title' => '稽核狀況'
				));
	}
	public function feedback($ri_id){
		// dd($ri_id);
		return View::make('auditee/feedback')->with(
			array(
				'title' => '矯正/預防填寫',
				'report' => PiaReportItem::find($ri_id)
			));
	}
}
