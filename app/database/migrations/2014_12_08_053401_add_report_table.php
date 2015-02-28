<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("report", function($table)
		{
			$table->increments('r_id');
			$table->datetime('r_time');
			$table->integer('a_id');
			$table->string('r_serial');
			$table->string('r_msg');
			$table->datetime('r_auth_signed')->nullable();
			$table->datetime('r_auditor_signed')->nullable();
			$table->datetime('r_comm_signed')->nullable();
		});

		Schema::create("report_item", function($table)
		{
			$table->increments('ri_id');
			$table->integer('r_id');
			$table->string('ri_base');
			$table->string('ri_discover');
			$table->string('ri_recommand');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("report");
		Schema::dropIfExists("report_item");
	}

}
