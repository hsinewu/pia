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

		parent::save($options);
	}

	public $form_fields = array(
		// name => type, placeholder, display_text
		'event_id' => array('select.event','稽核事件','稽核事件'),
		// 'org_id' => array('select.org','組織','組織'),
		'p_id' => array('select.person','稽核人','稽核人'),
		//'ad_org_id' => array('select.org','受稽組織','受稽組織'),
		'ad_dept_id' => array('select.dept','受稽單位','受稽單位'),
		'ad_time_from' => array('date','開始時間','開始時間'),
		'ad_time_end' => array('date','結束時間','結束時間'),
	);

}
