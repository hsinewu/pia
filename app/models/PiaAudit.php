<?php

class PiaAudit extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'auditor';
	protected $primaryKey = 'a_id';
	// protected $text = 'auditor_name';

	public $info_table_columns = array(
		"a_id" => "#",
		"p_name" => "稽核人",
		"ad_time_from" => "時間",
		"dept_name" => "受稽單位"
	);
	public $info_table_leftJoin = array(
		array('person','p_id','p_id'),
		array('dept','ad_dept_id','dept_id')
	);

	public function save(array $options = array()){

		// hope I can remove this stupid thing in the future...
		if(is_null($this->a_id))
			$this->a_id = DB::table($this->table)->max('a_id') + 1;
		if(!$this->org_id)
			$this->org_id = "NCHU";
		if(!$this->ad_org_id)
			$this->ad_org_id = "NCHU";
		$validator = Validator::make
		(
		    array(
	    		"event_id" => $this->event_id,
	    		"p_id" => $this->p_id,
	    		"ad_dept_id" => $this->ad_dept_id,
	    		"ad_time_from" => $this->ad_time_from,
	    		"ad_time_end" => $this->ad_time_from
		    	),
		    array(
	        'event_id' => 'required',
	        'p_id' => 'required',
	        'ad_dept_id' => 'required',
					"ad_time_from" => 'required',
					"ad_time_end" => 'required'
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public function new_report()
	{
		$report = $this->report();
		//var_dump($report);
		//die();
		if($report->count()){
			$report = $report->first();
			if($report->status!='暫存'){
				throw new Exception("this audit is already reported!");
			}

		}else{
			$report = new PiaReport();
			$report->a_id = $this->a_id;
			$report->r_time = date("Y-m-d H:i:s");
			$report->r_serial = $this->ad_org_id . "-" . $this->dept()->first()->code . "-" . ($this->count_dept_report() + 1);
		}
		return $report;
	}

	public $form_fields = array(
		// name => type, placeholder, display_text
		'event_id' => array('select.event','稽核事件','稽核事件'),
		// 'org_id' => array('select.org','組織','組織'),
		'p_id' => array('select.person','稽核人','稽核人'),
		//'ad_org_id' => array('select.org','受稽組織','受稽組織'),
		'ad_dept_id' => array('select.dept','受稽單位','受稽單位'),
		// 'ad_time_from' => array('date','開始時間','開始時間'),
		// 'ad_time_end' => array('date','結束時間','結束時間'),
		'ad_time_from' => array('date_timepicker_start','開始時間','開始時間'),
		'ad_time_end' => array('date_timepicker_end','結束時間','結束時間'),
	);

	public function count_dept_report()
	{
		return DB::table("auditor as B")->where("B.event_id","=",$this->event_id)->where("B.ad_org_id","=",$this->ad_org_id)->where("B.ad_dept_id","=",$this->ad_dept_id)->join("report as A","B.a_id","=","A.a_id")->count();
	}

	public function dept()
	{
		return $this->hasOne("PiaDept","dept_id","ad_dept_id");
	}

	public function report()
	{
		return $this->hasOne("PiaReport","a_id");
	}

}
