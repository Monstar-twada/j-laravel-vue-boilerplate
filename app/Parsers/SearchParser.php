<?php

namespace App\Parsers;
use Illuminate\Http\Request;

abstract class SearchParser
{


    abstract public function parse(array $searches);

    public function parseQueryString($str,$delimiter = '&')
    {
        if($str[0] == '?'){
            $str = substr($str,1);
        }
        $query_body = [];
        foreach(explode('&',$str) as $q){
            $split_equals = explode('=',$q);
            $value = $split_equals[1];
            $key = $split_equals[0];
            $query_body[$key] = $value;
        }
        return $query_body;
    }

    public function parseString($str,$key = 'search',$delimiter = '&')
    {
        $key_start_index = strpos($str,$key);
        $key_end_index = strpos($str,$delimiter,$key_start_index);

        if($key_end_index === false){
            $key_end_index = strlen($str);
        }else{
            $key_end_index -= 1;
        }

        $search = str_replace("{$key}=",'',substr($str,$key_start_index,$key_end_index));

        return $this->parserSearchData($search);
    }

    public function parseRequest(Request $request,$key = 'search')
    {
        $result = [];
        if($request->has($key)){
            $result = $this->parse($this->parserSearchData($request->input($key)));
        }

        return $result;
    }

    protected function getRangeValue($from,$to,$searches,$suffix=null)
    {
        $from = isset($searches[$from]) ? $searches[$from] : null;
        $to = isset($searches[$to]) ? $searches[$to] : null;
        if($suffix){
            $from = $from ? $from.$suffix : $from;
            $to = $to ? $to.$suffix : $to;
        }
        $value = array_filter([$from,$to]);
        return implode($value," 〜 ");
    }

    protected function parserSearchData($search)
    {
        if(is_array($search)){
            return $search;
        }
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }
}
