<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RiStatusEsidTimestamps extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('report_item', function($table)
		{
		    $table->string('ri_status')->default('表單待填');;
		    $table->integer('es_id')->nullable();
		    $table->datetime('confirm_timestamp1')->nullable();
		    $table->datetime('confirm_timestamp2')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('report_item', function($table)
		{
		    $table->dropColumn('ri_status');
		    $table->dropColumn('es_id');
		    $table->dropColumn('confirm_timestamp1');
		    $table->dropColumn('confirm_timestamp2');
		});
	}

}
