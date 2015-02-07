<?php

class PiaEmailSign extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'email_sign';
	protected $primaryKey = 'es_id';

	public function save(array $options = array()){

		$validator = Validator::make
		(
		    array(
	    		// "es_id" => $this->es_id,
	    		"r_id" => $this->r_id,
	    		"es_code" => $this->es_code,
	    		"es_used" => $this->es_used,
		    	),
		    array(
		        // "es_id" => 'required',
		        "r_id" => 'required',
		        "es_code" => 'required',
		        "es_used" => 'required',
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	// public function get_base_str(){
	// 	$src = Config::get('pia_report');
	// 	foreach ($src as $key => $value) {
	// 		if($value->value == $this->ri_base)
	// 			return $value->text;
	// 	}
	// }

	public function report()
	{
		return $this->hasOne("PiaReport","r_id","r_id");
	}

	public function sign()
	{
		if($this->es_used)
			throw new Exception("已經確認過了！");

		$key = PiaReport::get_key2sign()[$this->es_type];

		$report = $this->report()->firstOrFail();
		$report->$key = date("Y-m-d H:i:s");
		$report->update_level();
		$report->save();

		$this->es_used = true;
		$this->save();
		return $report;
	}

}
