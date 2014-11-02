<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Person extends PiaBase implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'person';

	public $info_table_columns = array(
		"p_id" => "#",
		"dept_name" => "系所",
		"p_name" => "姓名",
		"p_mail" => "信箱"
	);
	public $info_table_leftJoin = array(
		array('dept','dept_id','dept_id')
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('p_pass', 'remember_token');

	public static function get($email,$pwd){
		return Person::where('p_mail', $email)->where('p_pass',$pwd)->first();
	}
}
