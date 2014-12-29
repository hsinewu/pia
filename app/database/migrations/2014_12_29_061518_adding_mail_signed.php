<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingMailSigned extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("email_sign", function($table)
		{
			$table->increments('es_id');
			$table->integer('r_id');
			$table->string('es_code');
			$table->boolean('es_used')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('email_sign');
	}

}
