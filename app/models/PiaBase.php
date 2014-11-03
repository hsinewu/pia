<?php

class PiaBase extends Eloquent {

    public function info_table(){
        $query = DB::table($this->table);
        $alias = '';
        foreach ($this->info_table_leftJoin as $join){
            $alias .= 'A';
            $query = $query->join($join[0] . " AS $alias", $this->table . "." . $join[1], '=', "$alias." . $join[2]);
        }
        return $query->select(array_keys($this->info_table_columns))->get();
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

}
