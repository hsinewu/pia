<?php

class PiaReportItem extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report_item';
  	protected $guarded = array('ri_id', 'r_id');
	protected $primaryKey = 'ri_id';

	public $status_list = ['表單待填','主管否決','組長否決','代填','待主管簽署','待組長簽署','完成'];

	public function save(array $options = array()){
		if(!isset($this->ri_status))
			$this->ri_status = '表單待填';

		switch($this->ri_status){
		case "表單待填":
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
	    		"ri_base" => $this->ri_base,
	    		"ri_discover" => $this->ri_discover,
	    		"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
			];
			$rule = [
				// "ri_id" => 'required',
				// "r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
			];
			break;
		case "代填":
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
	    		"ri_base" => $this->ri_base,
	    		"ri_discover" => $this->ri_discover,
	    		"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
				"handler_name" => $this->handler_name,
				"handler_email" => $this->handler_email,
			];
			$rule = [
				// "ri_id" => 'required',
				// "r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
				"handler_name" => 'required',
				"handler_email" => 'required|email',
			];
			break;
		default:
			throw new Exception("Invalid status!");
		}
		$validator = Validator::make($data_to_validate,$rule);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public function get_base_str(){
		$src = Config::get('pia_report');
		foreach ($src as $key => $value) {
			if($value->value == $this->ri_base)
				return $value->text;
		}
	}

	public function report(){
		return $this->hasOne('PiaReport','r_id','r_id');
	}
}
