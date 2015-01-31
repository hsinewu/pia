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
		$item = PiaReportItem::find($ri_id);
		$report = $item->report()->first();
		$auditor = $report->auditor()->first();
		$auditee = $report->auditee()->first();
		return View::make('auditee/feedback')->with(
			array(
				'report' => $report,
				'auditor' => $auditor,
				'auditor_dept' => $auditor->dept()->first(),
				'auditee_dept'=> $auditee,
				'reportItem' => $item,
			));
	}

	public function feedback_process($ri_id){

	}

	public function view_report($id){
		$report = PiaReport::findOrFail($id);
		return View::make('report')->with(
		array(
			'report' => $report,
			'items' => $report->items()->get(),
			"download_route" => 'auditee_download_report',
			'title' => '稽核報告預覽：' . $report->r_serial,
		));
	}

	public function download_report($id){
		$report = PiaReport::findOrFail($id);
		return Response::download($report->gen_paper(), "$report->r_serial 稽核報告.pdf");
	}
}
