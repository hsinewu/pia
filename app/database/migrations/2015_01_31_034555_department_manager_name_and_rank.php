<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentManagerNameAndRank extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dept', function($table)
		{
		    $table->string('manager_name')->default('');
		    $table->string('manager_rank')->default('主管');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dept', function($table)
		{
		    $table->dropColumn('manager_name');
		    $table->dropColumn('manager_rank');
		});
	}

}
