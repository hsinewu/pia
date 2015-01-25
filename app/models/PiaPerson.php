<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PiaPerson extends PiaBase implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'person';
	protected $primaryKey = 'p_id';
	protected $text = 'p_name';
	public $info_table_columns = array(
		"p_id" => "#",
		"dept_name" => "系所",
		"p_name" => "姓名",
		"p_mail" => "信箱"
	);
	public $info_table_leftJoin = array(
		array('dept','dept_id','dept_id')
	);

	public function getLevel_key_value(){
		$object = new stdClass();

		return array(
			(object)array("value" => 0, "text" => "受稽人員"),
			(object)array("value" => 1, "text" => "稽核人員"),
			(object)array("value" => 2, "text" => "管理員"),
			(object)array("value" => 3, "text" => "資安暨 個資保護 稽核小組"),
			(object)array("value" => 4, "text" => "資訊安全暨 個人資料保護 推動委員會"),
		);
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('p_pass', 'remember_token');

	public static function get($email,$pwd){
		return self::where('p_mail', $email)->where('p_pass',$pwd)->first();
	}

	public $form_fields = array(
		// name => type, placeholder, display_text
		// 'org_id' => array('select.org','學校','學校'),
		'p_id' => array('text','編號','編號'),
		'dept_id' => array('select.dept','院系','院系'),
		'p_name' => array('text','名稱','名稱'),
		'p_phone' => array('text','電話','電話'),
		'p_mail' => array('email','信箱','信箱'),
		'p_title' => array('text','職稱','職稱'),
		'p_pass' => array('password','更改密碼','密碼'),
		'p_level' => array('checkbox.level','權限','權限'),
	);
	public function save(array $options = array()){
		$validator = Validator::make
		(
		    array(
		    		'p_id' => $this->p_id,
		    		'dept_id' => $this->dept_id,
		    		'p_name' => $this->p_name,
		    		'p_phone' => $this->p_phone,
		    		'p_mail' => $this->p_mail,
		    		'p_title' => $this->p_title,
		    		'p_pass' => $this->p_pass
		    	),
		    array(
		        'p_id' => 'required',
		        'dept_id' => 'required',
		        'p_name' => 'required',
		        'p_phone' => 'required',
		        'p_mail' => 'required|email',
		        'p_title' => 'required',
		        'p_pass' => 'required'
		    )
		);

		//die($validator->fails());
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public function audit(){
		return $this->hasMany("PiaAudit","p_id","p_id")->join('dept','ad_dept_id','=','dept_id');
	}
	public function is($type){
		switch ($type) {
			case 'admin':
				return $this->p_level & 1;
			case 'audit':
				return $this->p_level & 2;
			case 'auditee':
				return $this->p_level & 4;
			default:
				dd('In PiaPerson.php unknown user type');
		}
	}
	public function isAdmin(){
		return $this->p_level == 2;
	}

	public static function get_pia_team()
	{
		return self::where('p_level','=',3)->firstOrFail();
	}

	public static function get_pia_committee()
	{
		return self::where('p_level','=',4)->firstOrFail();
	}
}
