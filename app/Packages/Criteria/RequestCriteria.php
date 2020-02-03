<?php
namespace App\Packages\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface ;
use Carbon\Carbon;
use Collator;
use App\Packages\Searchables\Contracts\SearchableInterface as Searchable;

/**
 * Class RequestCriteria
 * @package Prettus\Repository\Criteria
 */
abstract class RequestCriteria implements CriteriaInterface
{
    protected $request;
    protected $searchables;

    /**
     * @var \Illuminate\Http\Request
     */
    public function __construct(Request $request, Searchable $searchables)
    {
        $this->request = $request;
        $this->searchables = $searchables;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $this->search($model);
        $model = $this->sort($model);
        return $model;
    }

    public abstract function search($model);
    public abstract function sort($model);

    public function getNumberOnly($val)
    {
        return  floatval(preg_replace('/[^-0-9.]/','',$val));
   }

    public function collatorSort($lang,$values,$order = "asc"){
        $arr = $values;
        $collator = new Collator($lang);
        $collator->sort($arr);
        return $order == 'asc' ? $arr : array_reverse($arr);
    }

    public function createFromDate($value,$is_last=false)
    {
        $value = preg_replace("@[/\\\]@","-",$value);
        $value = array_filter(explode('-',$value));
        $year = $value[0];
        $month = isset($value[1]) ? $value[1] : 1;
        $date = null;

        if(count($value) <= 2){
            if($is_last){
                //get the last date of the month or month and date if month is not specified
                $month = count($value) == 1 ? $month = 12 : $month;
                $date = Carbon::createFromDate($year,$month+1,0);
            }else{
                $date = Carbon::createFromDate($year,$month,1);
            }
            return $date;
        }

        $day = isset($value[2]) ? $value[2] : 1;
        $date = Carbon::createFromDate($year,$month,$day);

        return $date;
    }


    public function fromWhere($model,$field,$value){
        $value = $this->getNumberOnly( $value );
        $rounded = floor($value);
        if($value != $rounded){
            return $model->where($field,'>=',$value);
        }

        return $model->where($field,'>=',$rounded);
    }

    public function toWhere($model,$field,$value){
        $value = $this->getNumberOnly( $value );
        if($value == 0){
            return $model->where($field,'<=',$value);
        }
        $rounded = floor($value);
        if($value != $rounded){
            $decimals = substr($value,strpos($value,'.'),strlen($value));
            $up = (float)(str_pad("0.",strlen($decimals),"0",STR_PAD_RIGHT)."1");
            return $model->where($field,'<',$value+$up);
        }
        return $model->where($field,'<=',$rounded);
        /*
         * if value is greater than 0 add 1
         * if value is lesser than 0 subtract 1
         */
        $up = $value  == 0  ? 0 : $value < 0 ? -1 : 1;
        //$up = $value  == 0  ? 0 : $value < 0 ? -0.9 : 0.9;

        return $model->where($field,'<',$rounded+$up);
    }

    public function fromHaving($model,$field,$value){
        $value = $this->getNumberOnly( $value );
        $rounded = floor($value);
        if($value != $rounded){
            return $model->having($field,'>=',$value);
        }

        return $model->having($field,'>=',$rounded);
    }

    public function toHaving($model,$field,$value){
        $value = $this->getNumberOnly( $value );
        if($value == 0){
            return $model->having($field,'<=',$value);
        }
        $rounded = floor($value);
        if($value != $rounded){
            $decimals = substr($value,strpos($value,'.'),strlen($value));
            $up = (float)(str_pad("0.",strlen($decimals),"0",STR_PAD_RIGHT)."1");
            return $model->having($field,'<',$value+$up);
        }
        return $model->having($field,'<=',$rounded);
        /*
         * if value is greater than 0 add 1
         * if value is lesser than 0 subtract 1
         */
        $up = $value  == 0  ? 0 : $value < 0 ? -1 : 1;
        //$up = $value  == 0  ? 0 : $value < 0 ? -0.9 : 0.9;

        return $model->having($field,'<',$rounded+$up);
    }

    public function getColumn($field)
    {
		$column = $field;
		if(is_array($this->mapping) && isset($this->mapping[$field])){
			$column = $this->mapping[$field];
        }
        $table = $this->table;
        if(is_array($column)){
            array_walk_recursive($column,function(&$column,$key) use($table){
                return !$table ? $column : $table.'.'.$column;
            });
            return $column;
        }
        return !$table ? $column : $table.'.'.$column;
    }

}
