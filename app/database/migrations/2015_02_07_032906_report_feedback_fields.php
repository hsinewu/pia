<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportFeedbackFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('report_item', function($table)
		{
		    $table->string('handler_name')->nullable();
		    $table->string('handler_email')->nullable();
		    $table->string('analysis')->nullable();
		    $table->string('rectify_measure')->nullable();
		    $table->boolean('scan_help')->nullable();
		    $table->datetime('rec_finish_date')->nullable();
		    $table->string('precautionary_measure')->nullable();
		    $table->datetime('pre_finish_date')->nullable();
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
		    $table->dropColumn('handler_name');
		    $table->dropColumn('handler_email');
		    $table->dropColumn('analysis');
		    $table->dropColumn('rectify_measure');
		    $table->dropColumn('scan_help');
		    $table->dropColumn('rec_finish_date');
		    $table->dropColumn('precautionary_measure');
		    $table->dropColumn('pre_finish_date');
		});
	}

}
