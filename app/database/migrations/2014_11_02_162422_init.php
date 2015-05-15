<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration {

	// protected $group_tbl = "group";
	protected $dept_tbl = "dept";
	protected $person_tbl = "person";
	protected $auditor_tbl = "auditor";
	protected $event_tbl = "event";

	protected $def_org = "NCHU";
	protected $def_dept;

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create($this->group_tbl, function($table)
		// {
		// 	$table->string('org_id');
		// 	$table->integer('group_id')->nullable();
		// 	$table->string('group_id')->nullable();
		// });

		Schema::create($this->dept_tbl, function($table)
		{
			$table->increments('dept_id');
			$table->integer('group_id')->nullable();
			$table->string('org_id')->nullable();
			$table->string('dept_name')->unique();
			$table->string('code')->default("");
		});

		echo "Please input the dept source data table name: (input nothing to ignore importing)\n";
		$src_tbl = "";
		$src_tbl = readline();
		if($src_tbl){
			$data = DB::select("select dpt_id, dept_name,dept_parent, dept_chr from $src_tbl;");
			$inserts = array();
			foreach ($data as $key => $value) {
				$inserts[] = array(
					'dept_id' => $value->dpt_id, // not dept_id ...
					'group_id' => $value->dept_parent,
					'org_id' => $this->def_org,
					'dept_name' => $value->dept_name,
					'code' => $value->dept_chr
				);
			}
			DB::table($this->dept_tbl)->insert($inserts);
		}

		$this->def_dept = DB::table($this->dept_tbl)->insertGetId(array(
			// 'dept_id' => 0,
			'group_id' => null,
			'org_id' => $this->def_org,
			'dept_name' => "Default"
		));

		echo "An default dept has been create with name valuing 'Default'!\n";

		// ===============================

		Schema::create($this->person_tbl , function($table)
		{
			$table->string('p_id')->unique();
			$table->string('org_id');
			$table->integer('dept_id');
			$table->string('p_name');
			$table->string('p_phone')->nullable();
			$table->string('p_mail');
			$table->string('p_title')->nullable();
			$table->string('p_pass');
			$table->integer('p_level');
		});

		echo "Please input the person source data table name: (input nothing to ignore importing)\n";
		$src_tbl = "";
		$src_tbl = readline();

		$dept = DB::table($this->dept_tbl)->lists('dept_id', 'dept_name');

		if($src_tbl){
			$data = DB::select("select user_account,user_name,user_passwd,user_mail,user_dept from $src_tbl;");
			$inserts = array();
			foreach ($data as $key => $value) {
				$inserts[] = array(
					'p_id' => $value->user_account,
					'org_id' => $this->def_org,
					'dept_id' => array_key_exists($value->user_dept,$dept)?$dept[$value->user_dept]:$this->def_dept,
					'p_name' => $value->user_name,
					'p_mail' => $value->user_mail,
					'p_pass' => $value->user_passwd,
					'p_level' => 4 // default auditee level
				);
			}
			DB::table($this->person_tbl)->insert($inserts);
		}

		DB::table($this->person_tbl)->insert(array(
			'p_id' => "admin",
			'org_id' => $this->def_org,
			'dept_id' => $this->def_dept,
			'p_name' => "admin",
			'p_mail' => "admin",
			'p_pass' => md5("admin"),
			'p_level' => 1 // admin level
		));

		echo "An admin user has been create with p_id,p_name,p_mail and p_pass valuing 'admin'!\n";

		// ===============================

		Schema::create($this->auditor_tbl, function($table)
		{
			$table->increments('a_id'); // caution: original db structure doesnt have this
			$table->string('event_id');
			$table->string('org_id');
			$table->string('p_id');
			$table->string('ad_org_id');
			$table->string('ad_dept_id');
			$table->datetime('ad_time_from');
			$table->datetime('ad_time_end');
		});

		// ===============================

		Schema::create($this->event_tbl, function($table)
		{
			$table->string('event_id');
			$table->string('event_name');
			$table->datetime('event_from');
			$table->datetime('event_end');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists($this->dept_tbl);
		Schema::dropIfExists($this->dept_tbl);
		Schema::dropIfExists($this->person_tbl);
		Schema::dropIfExists($this->auditor_tbl);
		Schema::dropIfExists($this->event_tbl);
	}

}
