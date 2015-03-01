<?php

class PiaGlobal extends PiaBase {

    protected $table = 'global';
    protected $text = 'value';
    protected $primaryKey = 'key';

    public $creatable = false;
    public $deletable = false;

    public $info_table_columns = array(
        "name" => "屬性名稱",
        "value" => "屬性值",
    );

    public $global_settings = [
        // key => [chinese_name,validation_rule],
        'pia_team_email' => ['稽核小組信箱','required|email'],
        'pia_committee_email' => ['稽核委員會信箱','required|email'],
    ];

    public $form_fields = array(
        'name' => array('readonly_text','屬性名稱','屬性名稱'),
        'value' => array('text','屬性值','屬性值'),
    );

    public static function find($id, $columns = Array()){

        $r = PiaGlobal::where('key', $id)->first();
        $r->name = $r->global_settings[$r->key][0];
        return $r;
    }

    public function info_table($more_columns = array()){
        $glo = self::all()->all();
        foreach ($glo as $key => $g) {
            $glo[$key]->name = $this->global_settings[$glo[$key]->key][0];
        }
        // dd($glo[0]->name);
        return $glo;
    }

    public function save(array $options = array()){
        unset($this->name);
        $validator = Validator::make
        (
            array(
                // "key" => $this->key,
                "value" => $this->value,
                ),
            array(
                // 'key' => 'required',
                "value" => $this->global_settings[$this->key][1],
            )
        );

        if(!array_key_exists($this->key,$this->global_settings))
            throw new Exception("You can not create an unknown property!");
        if($validator->fails()){
            throw new Exception($validator->messages());
        }


        parent::save($options);
    }

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

    public static function get_test_email(){
        return self::grab("test_email");
    }

    public function delete(){
        if(array_key_exists($this->key,$this->global_settings))
            throw new Exception("You can not delete this item!");
        parent::delete();
    }

}
