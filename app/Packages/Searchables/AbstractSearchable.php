<?php

namespace App\Packages\Searchables;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Packages\Searchables\Contracts\SearchableInterface;

abstract class AbstractSearchable implements SearchableInterface
{
    protected $fields = [];

    public function __construct(Request $request)
    {
        $request = \App::make(Request::class);
        $this->fields = $this->searchables($request->get('search',[]))
    }

    abstract public function searchables($searches);

    public function get(){
        return $this->fields;
    }

    public function only(array $keys){
        $this->fields = Arr::only($this->fields,$keys);
        return $this;
    }

    public function except(array $keys){
        $this->fields = Arr::except($this->fields,$keys);
        return $this;
    }

    public function isEmpty()
    {
        return count($this->fields) <= 0;
    }

    public function keys()
    {
        return array_keys($this->fields);
    }

    public function values()
    {
        return array_values($this->fields);
    }

    protected function array_map($arr,$path,$strict=false){
        $map = [];
        foreach($arr as $key => $value){
            $map[$key] =  is_array($value) ?  Arr::get($value,$path,$key) : $strict ? null : $value;
        }
        return $map;
    }
}
