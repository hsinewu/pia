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
			$table->increments('dept_id');
			$table->integer('group_id')->nullable();
			$table->integer('org_id')->nullable();
			$table->string('dept_name')->unique();
		});

		Schema::create('person', function($table)
		{
			$table->increments('p_id');
			$table->integer('org_id');
			$table->integer('dept_id');
			$table->string('p_name')->unique();
			$table->string('p_phone');
			$table->string('p_mail');
			$table->string('p_title');
			$table->string('p_pass');
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
