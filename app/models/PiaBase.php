<?php

class PiaBase extends Eloquent {

    public function info_table(){
        $query = DB::table($this->table);
        foreach ($this->info_table_leftJoin as $join)
            $query = $query->join($join[0] . " AS A", $this->table . "." . $join[1], '=', "A." . $join[2]);
        return $query->select(array_keys($this->info_table_columns))->get();
    }

}
