<?php

class PiaGlobal extends PiaBase {

    protected $table = 'global';
    protected $text = 'key';
    public $info_table_columns = array(
        "key" => "鍵",
        "value" => "值",
    );

    public static function grab($key){
        $glo = self::where("key","=",$key)->firstOrFail();
        return $glo->value;
    }

    public static function get_pia_team_email(){
        return self::grab("pia_team_email");
    }

    public static function get_pia_committee_email(){
        return self::grab("pia_committee_email");
    }

}
