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

	public function item(){
		return $this->hasMany("PiaReportItem","r_id");
	}

}
