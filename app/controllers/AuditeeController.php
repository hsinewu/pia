<?php

class AuditeeController extends Controller {

	public function status(){
		return View::make('auditee/status')->with(
			array(
				'audits' => PiaAudit::where('ad_dept_id', Session::get('user')->dept_id)->get(),
				'title' => '稽核狀況'
				));
	}
}
