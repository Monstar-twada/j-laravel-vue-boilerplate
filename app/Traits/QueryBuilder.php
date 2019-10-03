<?php

namespace App\Traits;
use DB;

trait QueryBuilder
{
    public function build_table_columns($name,$columns)
    {
        foreach($columns as $index => $column){
            if(!is_array($column)){
                $columns[$index] = "`{$name}`.`{$column}`";
            }else{
                $col = $column['column'];
                $as = $column['as'];

                $result = "`{$name}`.`{$col}`";
                if(isset($column['func'])){
                    $func = $column['func'];
                    $result = "{$func}($result)";
                }
                $result .= " AS `{$as}`";
                $columns[$index] = $result;
            }
        }
        return $columns;
    }

    private function statement($statement)
    {
        try{
            DB::statement($statement);
        }catch(\Illuminate\Database\QueryException $ex){
        }
    }

    /*
     * CREATE A UNION QUERY OF MULTIPLE TABLES of same nature
     * i.e `usonar_original_{x}`
     */
    private function create_tables_union_query($name)
    {
        $con = \Config::get('database.default');
        $config = \Config::get('database.connections.'.$con);

        $count = ((array)DB::select("SELECT COUNT(*) AS `count` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = '{$config['database']}' AND `TABLE_NAME` LIKE '{$name}_%'")[0])['count'];
        $union_tables = "";
        for($i = 0; $i < $count; $i++){
            $union_tables .= "SELECT * FROM {$name}_{$i}";
            if($i+1 < $count){
                $union_tables .=" UNION ALL ";
            }
        }
        return $union_tables;
    }

    /**
     * Combines SQL and its bindings
     *
     * @param \Eloquent $query
     * @return string
     */
    public function getSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}
