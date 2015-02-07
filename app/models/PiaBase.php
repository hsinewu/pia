<?php

class PiaBase extends Eloquent {

    public $timestamps = false;
    public $creatable = true;
    public $deletable = true;

    public function info_table($more_columns = array()){
        $query = DB::table($this->table);
        $alias = '';
        $tbl_prefix = Config::get('database.connections.mysql.prefix');
        // die($tbl_prefix);
        foreach ($this->info_table_leftJoin as $join){
            $alias .= 'A';
            $query = $query->join($join[0] . " AS $tbl_prefix$alias", $this->table . "." . $join[1], '=', "$alias." . $join[2]);
        }
        return $query->select(array_merge($more_columns,array_keys($this->info_table_columns)))->get();
    }

    public function info()
    {
        $alias = '';
        $self = $this;
        $tbl_prefix = Config::get('database.connections.mysql.prefix');
        foreach ($this->info_table_leftJoin as $join){
            $alias .= 'A';
            $self = $self->join($join[0] . " AS $tbl_prefix$alias", $this->table . "." . $join[1], '=', "$alias." . $join[2]);
        }
        return $self;
    }

    public function getPK(){
        return $this->primaryKey;
    }

    public function getTable(){
        return $this->table;
    }

    public function gen_key_value(){
        $pk = $this->primaryKey;
        $text = $this->text;
        return DB::table($this->table)->get(array("$pk as value","$text as text"));
    }

    public function fill_field($data){
        foreach ($this->form_fields as $key => $value) {
            switch ($value[0]) {
                case 'password':
                    if(strlen($data[$key]))
                        $data[$key] = md5($data[$key]);
                    else
                        $data[$key] = $this->$key;
                    break;
            }
            $this->$key = $data[$key];
        }
        return $this;
    }

}
