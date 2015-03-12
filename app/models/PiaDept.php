<?php

class PiaDept extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'dept';
	protected $primaryKey = 'dept_id';
	protected $text = 'dept_name';

	public $info_table_columns = array(
		"dept_id" => "#",
		"org_id" => "學校",
		"code" => "機關代碼",
		"dept_name" => "單位名稱",
		"group_name" => "院系",
	);
	public $info_table_leftJoin = array(
		array('dept','group_id','dept_id')
	);

	public function info_table($more_columns = array()){
	    return DB::select('select `A`.`dept_id`,`A`.`org_id`,`A`.`code`,`B`.`dept_name` as `group_name`,`A`.`dept_name` from `dept` as `A` left join `dept` as `B` on `A`.`group_id` = `B`.`dept_id`');
	}

	public function save(array $options = array()){
		if(is_null($this->org_id))
			$this->org_id = "NCHU";

		// hope I can remove this stupid thing in the future...
		// if(is_null($this->dept_id))
		// 	$this->dept_id = DB::table($this->table)->max('dept_id') + 1;
		$validator = Validator::make
		(
		    array(
	    		// "group_id" => $this->group_id,
	    		"dept_name" => $this->dept_name,
	    		"email" => $this->email,
				"manager_name" => $this->manager_name,
				"manager_rank" => $this->manager_rank,
	    		//"code" => $this->code,
		    	),
		    array(
		        // 'group_id' => 'required',
				'dept_name' => 'required',
				'email' => 'required|email',
				"manager_name" => 'required',
				"manager_rank" => 'required',
				//'code' => 'required',
		    )
		);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public $form_fields = array(
		// name => type, placeholder, display_text
		'group_id' => array('select.dept','院系','院系'),
		// 'org_id' => array('select.org','學校','學校'),
		'dept_name' => array('text','單位名稱','單位名稱'),
		'manager_name' => array('text','主管姓名','主管姓名'),
		'manager_rank' => array('text','主管稱謂','主管稱謂'),
		'email' => array('email','主管信箱','主管信箱'),
		'code' => array('text','機關代碼','機關代碼'),
	);

}
