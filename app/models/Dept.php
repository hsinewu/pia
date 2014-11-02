<?php

class Dept extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'dept';

	public $info_table_columns = array(
		"dept_id" => "#",
		"org_id" => "學校",
		"group_name" => "院系",
		"dept_name" => "單位"
	);
	public $info_table_leftJoin = array(
		array('dept','group_id','dept_id')
	);

	public function info_table(){
	    return DB::select('select `A`.`dept_id`,`A`.`org_id`,`B`.`dept_name` as `group_name`,`A`.`dept_name` from `dept` as `A` left join `dept` as `B` on `A`.`group_id` = `B`.`dept_id`');
	}

}
