<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dept', function($table)
		{
			$table->integer('dept_id');
			$table->integer('group_id')->nullable();
			$table->string('org_id')->nullable();
			$table->string('dept_name')->unique();
		});

		Schema::create('person', function($table)
		{
			$table->string('p_id');
			$table->string('org_id');
			$table->integer('dept_id');
			$table->string('p_name')->unique();
			$table->string('p_phone');
			$table->string('p_mail');
			$table->string('p_title');
			$table->string('p_pass');
		});

		Schema::create('auditor', function($table)
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

		Schema::create('event', function($table)
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
		//
	}

}
