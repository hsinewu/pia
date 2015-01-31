<?php

class PiaReport extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report';
	protected $primaryKey = 'r_id';
	// protected $text = 'auditor_name';

	// public $info_table_columns = array(
	// 	"a_id" => "#",
	// 	"p_name" => "稽核人",
	// 	"ad_time_from" => "時間",
	// 	"dept_name" => "受稽單位"
	// );
	// public $info_table_leftJoin = array(
	// 	array('person','p_id','p_id'),
	// 	array('dept','ad_dept_id','dept_id')
	// );

	public static function get_key2sign(){
		return ["r_auth_signed","r_auditor_signed","r_comm_signed"];
	}

	public $level2status = ['暫存','儲存，等待簽署','尚餘兩個簽署','尚餘一個簽署','完成簽署'];

	public function save(array $options = array()){

		$validator = Validator::make
		(
		    array(
	    		// "r_id" => $this->r_id,
	    		"r_time" => $this->r_time,
	    		"a_id" => $this->a_id,
	    		"r_serial" => $this->r_serial,
	    		// "r_msg" => $this->r_msg,
	    		//"r_auth_signed" => $this->r_auth_signed,
	    		//"r_auditor_signed" => $this->r_auditor_signed,
	    		//"r_comm_signed" => $this->r_comm_signed,
		    	),
		    array(
		        // "r_id" => 'required',
		        "r_time" => 'required',
		        "a_id" => 'required',
		        "r_serial" => 'required',
		        // "r_msg" => 'required',
		        //"r_auth_signed" => 'required',
		        //"r_auditor_signed" => 'required',
		        //"r_comm_signed" => 'required',
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public function set_state_level($level)
	{
		if(!in_array($level, array_keys($this->level2status)))
			throw new Exception("Unknown level!");
		$this->status = $this->level2status[$level];
	}

	public function is_temp()
	{
		return $this->status == '暫存';
	}

	public function is_saved()
	{
		return $this->status == '儲存，等待簽署';
	}

	public function is_finished()
	{
		return $this->status == '完成簽署';
	}

	public function update_level()
	{
		$level = array_flip($this->level2status)[$this->status];
		if($level >= 1){
			$signed_cnt = 0;
			$key2sign = self::get_key2sign();
			for($i = 0; $i < 3; $i++)
				if($this->{$key2sign[$i]})
					$signed_cnt++;
			$level = 1 + $signed_cnt;
		}
		$this->set_state_level($level);
		return $this->is_finished();
	}

	public function new_item()
	{
		$item = new PiaReportItem();
		$item->r_id = $this->r_id;

		return $item;
	}

	public function audit()
	{
		return $this->hasOne('PiaAudit','a_id','a_id');
	}

	public function items()
	{
		return $this->hasMany('PiaReportItem','r_id','r_id');
	}

	public function auditor()
	{
		return $this->audit()->first()->hasOne("PiaPerson","p_id","p_id");
	}

	public function auditee()
	{
		return $this->audit()->first()->hasOne("PiaDept","dept_id","ad_dept_id");
	}

	public function gen_paper($hide_sign = false){
		$pdf_path = storage_path("report_pdf/" . $this->r_id );
		$full_pdf_path = $pdf_path . ".pdf";
		if(file_exists($full_pdf_path))
			unlink($full_pdf_path);
		if($hide_sign)
			PDF::html('paper/report', ['report' => $this, 'items' => $this->items()->get()->all(),"hide_sign" => true], $pdf_path);
		else
			PDF::html('paper/report', ['report' => $this, 'items' => $this->items()->get()->all()], $pdf_path);
		return $full_pdf_path;
	}

	public function send_email(){
		$dept = $this->audit()->first()->dept()->first();
		$pia_team_email = PiaGlobal::get_pia_team_email();
		$pia_committee_email = PiaGlobal::get_pia_committee_email();
		if(!filter_var($dept->email, FILTER_VALIDATE_EMAIL))
			throw new Exception("$dept->dept_name 的主管信箱不正確：$dept->email");
		if(!filter_var($pia_team_email, FILTER_VALIDATE_EMAIL))
			throw new Exception("資安暨 個資保護 稽核小組的信箱不正確：$pia_team_email");
		if(!filter_var($pia_committee_email, FILTER_VALIDATE_EMAIL))
			throw new Exception("資訊安全暨 個人資料保護 推動委員會的信箱不正確：$pia_committee_email");
		$GLOBALS['mail_des'] = [
			["mail_addr" => $dept->email, "rcv_name" => $dept->dept_name],
			["mail_addr" => $pia_team_email, "rcv_name" => "資安暨 個資保護 稽核小組"],
			["mail_addr" => $pia_committee_email, "rcv_name" => "資訊安全暨 個人資料保護 推動委員會:"],
		];

		define("pdf_name", $this->gen_paper(true));

		for($i = 0; $i < 3; $i++){
			$GLOBALS['mail_des']["cur"] = $i;

			$es = new PiaEmailSign();
			$es->r_id = $this->r_id;
			$es->es_code = md5($this->r_time . $this->r_msg . rand());
			$es->es_used = false;
			$es->es_type = $i;
			$es->es_to = $GLOBALS['mail_des'][$GLOBALS['mail_des']["cur"]]["mail_addr"];
			$es->save();

			Mail::send('emails/sign',
				['sign_url' => route("email_sign",$es->es_code),'report' => $this, 'items' => $this->items()->get()->all(),"hide_sign" => true],
				function($message)
				{
					$message
					->to(
						$GLOBALS['mail_des'][$GLOBALS['mail_des']["cur"]]["mail_addr"],
						$GLOBALS['mail_des'][$GLOBALS['mail_des']["cur"]]["rcv_name"]
					)
					->subject("您好，這是個資稽核系統，這裏有份稽核報告請您確認")
					->attach(pdf_name, array('as' => "個資稽核報告.pdf"));
				}
			);
		}


	}

}
