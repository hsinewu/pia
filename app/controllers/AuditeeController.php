<?php

class AuditeeController extends Controller {

	private $status = array(
		'new' => '表單待填',
		'mail' => '代填',
		'confirm1' => '待主管簽署',
		'reject1' => '主管否決',
		'confirm2' => '待組長簽署',
		'reject2' => '組長否決',
		'finish' => '完成'
	);

	private function sendMail($reportItem,$type){
		switch ($type) {
			case 'confirm1':
				$url_alias = 'rectify_email_sign';
				break;
			case 'confirm2':
				$url_alias = 'rectify_email_sign2';
				break;
			default:
				dd('wtf sendmail type');
				break;
		}
		//set up a new PiaEmailSign
		$es = new PiaEmailSign();
		$es->r_id = $reportItem->r_id;
		$es->gen_code();
		$es->es_used = false;
		$es->es_type = 3;
		//TODO: param to be fixed
		$es->es_to = PiaGlobal::get_test_email();
		$es->save();

		//Update ri's es_id field
		$reportItem->es_id = $es->es_id;
		$reportItem->save();

		//Actually send the email
		define("pdf_name", $reportItem->get_paper());

		Mail::send('emails/yes_no',
			[
			'url_alias' => $url_alias,
			'es_code' => $es->es_code,
			'content' => $reportItem->gen_html(),
			'type' => '矯正報告',
			],
			function($message){
				$message
				//TODO: param to be fixed
				->to( PiaGlobal::get_test_email(), '收件人' )
				->subject("個資稽核系統--矯正回報之確認")
				->attach(pdf_name, array('as' => "矯正回報.pdf"))
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
		if(!$item->is_editable()) throw new Exception('cant not be change again');
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

	private function feedback_processing($item,$assigned) // report_item as input
	{
		$input = Input::all();

		$item -> analysis = $input['reason'];
		$item -> rectify_measure = $input['rectify'];
		$item -> scan_help = isset($input['rectify_check']) ? true : false;
		$item -> rec_finish_date = $input['rectify_time'];
		$item -> precautionary_measure = $input['prevent'];
		$item -> pre_finish_date = $input['prevent_time'];
		$item -> fill_date = date("Y-m-d H:i:s");

		if(!$assigned){
			$item -> handler_name = Session::get('user')->p_name;
			$item -> handler_email = Session::get('user')->p_mail;
		}

		$item -> ri_status = $this->status['confirm1'];

		$item -> save();
		$item -> gen_paper();
		$this -> sendMail($item,'confirm1');
		Session::set("message","完成填寫，並且已寄信通知主管");
	}

	public function feedback_process($ri_id){
		try{
			$item = PiaReportItem::find($ri_id);
			if(!$item->is_editable()) throw new Exception('cant not be change again');
			$this->feedback_processing($item,false);
		}catch(Exception $e){
			Session::set("message","錯誤：" . $e->getMessage());
		}

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

		$report = $reportItem->report()->first();

		define("receiver", $reportItem->handler_name);
		define("mail_addr", $reportItem->handler_email);
		define("pdf_path", $report->get_paper());
		define("pdf_name", "$report->r_serial 稽核報告.pdf");
		Mail::send('emails/assign_report',
			[
			'es_code' => $es->es_code,
			'report_content' => $report->gen_html(),
			'report_item_content' => $reportItem->gen_html(true),
			'type' => '矯正報告',
			],
			function($message){
				$message
				->to( mail_addr, receiver )
				->subject("個資稽核系統 - 您被指定填寫矯正預防處理單")
				->attach(pdf_path, array('as' => pdf_name))
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
			$item->gen_paper();

			$this -> assign($item);
			Session::set("message","已完成矯正預防單填寫指定！");
			return Redirect::route('auditee_status');
		}catch (Exception $e) {
			Session::set("message",$e->getMessage());
			return Redirect::route('auditee_feedback',$ri_id);
		}
	}

	public function feedback_assign($code){
		try {
			$item = PiaReportItem::from_es_code($code);
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
					'es_code' => $code,
				));
		}catch (Exception $e) {
			echo($e->getMessage());
		}
	}

	public function feedback_assign_process($code){
		try{
			$item = PiaReportItem::from_es_code($code,true);
			if(!$item->is_assigned()) throw new Exception('cant not be change again');
			$this->feedback_processing($item,true);
		}catch(Exception $e){
			Session::set("message","錯誤：" . $e->getMessage());
		}

		return Redirect::to('/');
	}

	public function view_report($id){
		$report = PiaReport::findOrFail($id);
		return View::make('preview')->with(
		array(
			'content' => $report->gen_html(),
			"download_url" => route('auditee_view_report', $id),
			'title' => '稽核報告預覽：' . $report->r_serial,
		));
	}

	public function download_report($id){
		$report = PiaReport::findOrFail($id);
		return Response::download($report->get_paper(), "$report->r_serial 稽核報告.pdf");
	}

	public function sign($code,$yes_no){
		try {
		    $es = PiaEmailSign::where('es_code', '=', $code)->firstOrFail();
		} catch (Exception $e) {
		    Session::set("message",$e->getMessage());
		}
		//Is link used?Is type wrong?
		if($es->es_used == true)  dd("Link were used");
		if($es->es_type != 3)  dd("type error");

		//Find corresponding ri
		$ri = PiaReportItem::where('es_id','=',$es->es_id)->firstOrFail();
		//Check status
		if($ri->ri_status == $this->status['confirm2'] ||
			$ri->ri_status == $this->status['reject1'])
			dd("先前已簽署過");
		//Disable link
		$es->es_used = true;
		$es->save();

		$ri->confirm_timestamp1 = date("Y-m-d H:i:s");
		if($yes_no == 'yes'){
			$ri->ri_status = $this->status['confirm2'];
			$ri->save();
			$this -> sendMail($ri,'confirm2');
			Session::set("message","您已經認可這份矯正預防");
		}
		else if($yes_no == 'no'){
			$ri->ri_status = $this->status['reject1'];
			$ri->save();
			Session::set("message","您否決了這份矯正預防");
		}
		// dd($yes_no);
		return Redirect::to('/');
	}

	public function sign2($code,$yes_no){
		try {
		    $es = PiaEmailSign::where('es_code', '=', $code)->firstOrFail();
		} catch (Exception $e) {
		    Session::set("message",$e->getMessage());
		}
		//Is link used?Is type wrong?
		if($es->es_used == true)  dd("Link were used");
		if($es->es_type != 3)  dd("type error");

		//Find corresponding ri
		$ri = PiaReportItem::where('es_id','=',$es->es_id)->firstOrFail();
		if($ri->ri_status == $this->status['finish'] ||
			$ri->ri_status == $this->status['reject2'])
			dd("先前已簽署過");
		//Disable link
		$es->es_used = true;
		$es->save();

		$ri->confirm_timestamp2 = date("Y-m-d H:i:s");
		if($yes_no == 'yes'){
			$ri->ri_status = $this->status['finish'];
			$ri->save();
			Session::set("message","您已經認可這份矯正預防");
		}
		else if($yes_no == 'no'){
			$ri->ri_status = $this->status['reject2'];
			$ri->save();
			Session::set("message","您否決了這份矯正預防");
		}
		// dd($yes_no);
		return Redirect::to('/');
	}

	public function report_item($id){
		$item = PiaReportItem::findOrFail($id);
		return View::make('preview')->with(
		array(
			'content' => $item->gen_html(),
			"download_url" => route('auditee_download_report_item',$id),
			'title' => '校正預防報告預覽：',
		));
	}

	public function download_report_item($id){
		$item = PiaReportItem::findOrFail($id);
		return Response::download($item->get_paper(), $item->get_serial() . " 矯正預防報告.pdf");
	}
}
