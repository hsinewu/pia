<?php

class AuditeeController extends Controller {

	private $status = array(
		'new' => '表單待填',
		'mail' => '待電郵代填',
		'confirm1' => '待主管簽署',
		'reject1' => '主管否決',
		'confirm2' => '待組長簽署',
		'reject2' => '組長否決',
		'finish' => '完成'
	);

	private function sendMail1($reportItem){
		//set up a new PiaEmailSign
		$es = new PiaEmailSign();
		$es->r_id = $reportItem->r_id;
		//WARNING: encrypt what
		$es->es_code = md5( date("Y-m-d H:i:s") );
		$es->es_used = false;
		$es->es_type = 3;
		$es->es_to = PiaGlobal::get_test_email();
		$es->save();
		Mail::send('emails/yes_no',
			[
			'url_alias' => 'rectify_email_sign',
			'es_code' => $es->es_code,
			// 'sign_url' => route("rectify_email_sign",$es->es_code),
			'type' => '矯正報告',
			],
			function($message){
				$message
				->to( PiaGlobal::get_test_email(), '$Receiver' )
				->subject("個資稽核系統--矯正回報之確認")
				// ->attach(pdf_name, array('as' => "個資稽核報告.pdf"))
				;
		});
	}
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
		$auditee_dept = $report->auditee()->first();
		// if($item->handler_name!=NULL) $auditee = $item->handler_name;
		// else $auditee = Session::get('user');
		return View::make('auditee/feedback')->with(
			array(
				'report' => $report,
				'auditor' => $auditor,
				'auditor_dept' => $auditor->dept()->first(),
				'auditee'=> Session::get('user'),
				'auditee_dept'=> $auditee_dept,
				'reportItem' => $item,
			));
	}

	public function feedback_process($ri_id){
		$input = Input::all();
		$item = PiaReportItem::find($ri_id);
		if(!($item->ri_status == $this->status['new']
			||$item->ri_status == $this->status['mail']
			||$item->ri_status == '')) dd('cant not be change again');

		$item -> analysis = $input['reason'];
		$item -> rectify_measure = $input['rectify'];
		$item -> scan_help = isset($input['rectify_check']) ? true : false;
		$item -> rec_finish_date = $input['rectify_time'];
		$item -> precautionary_measure = $input['prevent'];
		$item -> pre_finish_date = $input['prevent_time'];

		$item -> ri_status = $this->status['confirm1'];
		$item -> save();

		$this -> sendMail1($item);

		return Redirect::route('auditee_status');
	}

	private function assign($reportItem){
		//set up a new PiaEmailSign
		$es = new PiaEmailSign();
		$es->r_id = $reportItem->r_id;
		//WARNING: encrypt what
		$es->es_code = md5( date("Y-m-d H:i:s") );
		$es->es_used = false;
		$es->es_type = 4;
		$es->es_to = $reportItem->handler_email;
		$es->save();

		$reportItem->es_id = $es->es_id;
		$reportItem->save();

		define("receiver", $reportItem->handler_name);
		define("mail_addr", $reportItem->handler_email);
		Mail::send('emails/assign_report',
			[
			'es_code' => $es->es_code,
			// 'sign_url' => route("rectify_email_sign",$es->es_code),
			'type' => '矯正報告',
			],
			function($message){
				$message
				->to( mail_addr, receiver )
				->subject("個資稽核系統--填寫矯正預防處理單")
				// ->attach(pdf_name, array('as' => "個資稽核報告.pdf"))
				;
		});
	}

	public function assign_process($ri_id){
		$input = Input::all();
		try {
			$item = PiaReportItem::find($ri_id);
			$item->handler_name = $input['auditee'];
			$item->handler_email = $input['auditee_mail'];

			if(!in_array($item->ri_status, [ $this->status['new'],
																			 $this->status['mail'],
																			 $this->status['reject1'],
																			 $this->status['reject2'] ]))
				throw new Exception("Illegal status!");

			$item->ri_status = $this->status['mail'];
			$item->save();

			$this -> assign($item);

			return Redirect::route('auditee_status');
		}catch (Exception $e) {
			Session::set("message",$e->getMessage());
			return Redirect::route('auditee_feedback',$ri_id);
		}
	}


	public function feedback_assign($code){
		try {
			if(PiaEmailSign::where('es_code',$code)->first()==NULL)
				throw new Exception("The link is invalid.");
			$es_id = PiaEmailSign::where('es_code',$code)->first()->es_id;
			if(PiaReportItem::where('es_id',$es_id)->first()==NULL)
				throw new Exception("The link is invalid.");
			$ri_id = PiaReportItem::where('es_id',$es_id)->first()->ri_id;
			$item = PiaReportItem::find($ri_id);
			$report = $item->report()->first();
			$auditor = $report->auditor()->first();
			$auditee_dept = $report->auditee()->first();
			return View::make('auditee/feedback_assign')->with(
				array(
					'report' => $report,
					'auditor' => $auditor,
					'auditor_dept' => $auditor->dept()->first(),
					'auditee'=> $item->handler_name,
					'auditee_dept'=> $auditee_dept,
					'reportItem' => $item,
				));
		}catch (Exception $e) {
			echo($e->getMessage());
		}
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
		return Response::download($report->get_paper(), "$report->r_serial 稽核報告.pdf");
	}

	public function sign($code,$yes_no){
		//TODO
		if($yes_no == 'yes')
			dd('You say yes');
		else if($yes_no == 'no')
			dd('You say no');
		dd($yes_no);
	}
}
