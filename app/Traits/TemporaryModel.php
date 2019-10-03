<?php

namespace App\Traits;
use DB;

trait TemporaryModel
{
    public function create_table()
    {
        $table = $this->getTable();
        $target = str_replace('tmp_','',$table);
        if($target){
            DB::statement("CREATE TABLE {$table} SELECT * FROM {$target} WHERE 1 = 2");
            $this->optimize();
            return;
        }
    }

    public function drop_table()
    {
        $table = $this->getTable();
        try{
            DB::statement('DROP TABLE '.$table);
        }catch(\Illuminate\Database\QueryException $ex){
        }
        return;
    }

    private function optimize()
    {
        $this->statement("ALTER TABLE {$this->table} CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`) USING BTREE");
    }

    private function statement($statement)
    {
        try{
            DB::statement($statement);
        }catch(\Illuminate\Database\QueryException $ex){
        }
    }

}
