<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GlobalSettingInit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		echo "========= Global setting init =========\n";
		$global_settings = (new PiaGlobal())->global_settings;
		foreach($global_settings as $k => $e){
			while(true){
				try{
					$tmp = new PiaGlobal();
					$tmp->key = $k;
					echo "Please input the " . $e[0] . " data: \n";
					$tmp->value = readline();
					$tmp->save();
				}catch(Exception $ex){
					echo $ex->getMessage() . "\n";
				}
			}
		}
		echo "========= Global setting init OK =========\n";
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
