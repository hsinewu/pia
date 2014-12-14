<?php

class PiaReportItem extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report_item';
	protected $primaryKey = 'ri_id';
	
	public function save(array $options = array()){

		$validator = Validator::make
		(
		    array(
	    		// "ri_id" => $this->ri_id,
	    		"r_id" => $this->r_id,
	    		"ri_base" => $this->ri_base,
	    		"ri_discover" => $this->ri_discover,
	    		"ri_recommand" => $this->ri_recommand,
		    	),
		    array(
		        // "ri_id" => 'required',
		        "r_id" => 'required',
		        "ri_base" => 'required',
		        "ri_discover" => 'required',
		        "ri_recommand" => 'required',
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

}
