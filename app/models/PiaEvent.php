<?php

class PiaEvent extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'event';
	protected $primaryKey = 'event_id';
	protected $text = 'event_name';

	public $info_table_columns = array(
		"event_id" => "#",
		"event_name" => "名稱",
		"event_from" => "開始",
		"event_end" => "結束"
	);
	public $info_table_leftJoin = array(
	);

	public $form_fields = array(
		// name => type, placeholder, display_text
		'event_id' => array('text','事件編號','事件編號'),
		'event_name' => array('text','事件名稱','事件名稱'),
		'event_from' => array('date_timepicker_start','開始時間','開始時間'),
		'event_end' => array('date_timepicker_end','結束時間','結束時間'),
	);
	public function save(array $options = array()){
		$validator = Validator::make
		(
		    array(
		    		"event_id" => $this->event_id,
		    		"event_name" => $this->event_name,
		    		"event_from" => $this->event_from,
		    		"event_end" => $this->event_end
		    	),
		    array(
		        'event_id' => 'required',
		        'event_name' => 'required',
		        'event_from' => 'required',//|date_format:
				'event_end' => 'required'
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}
}
