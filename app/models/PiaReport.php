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

	public function gen_view(){
		return View::make('paper/report', ['report' => $this, 'items' => $this->items()->get()->all()]);
	}

	public function gen_paper($fname){
		PDF::html('paper/report', ['report' => $this, 'items' => $this->items()->get()->all()], $fname);
	}

	public function send_email(){
		$dept = $this->audit()->first()->dept()->first();
		$mail_addr = $dept->email;
		$dept_name = $dept->dept_name;

		define("mail_addr", $mail_addr);
		define("dept_name", $dept_name);

		$pdf_path = storage_path("pdf_tmp/" . rand());
		$full_pdf_path = $pdf_path . ".pdf";
		if(file_exists($full_pdf_path))
			unlink($full_pdf_path);
		$this->gen_paper($pdf_path);
		define("pdf_name", $full_pdf_path);

		$es = new PiaEmailSign();
		$es->r_id = $this->r_id;
		$es->es_code = md5($this->r_time . $this->r_msg . rand());
		$es->es_used = false;
		$es->save();

		Mail::send('emails/sign', ['sign_url' => route("email_sign",$es->es_code)], function($message)
		{
			$message
			->to( mail_addr, dept_name )
			->subject("您好，這是個資稽核系統，這裏有份稽核報告請您確認")
			->attach(pdf_name, array('as' => "個資稽核報告.pdf"));
		});
	}

}
