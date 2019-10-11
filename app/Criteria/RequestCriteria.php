<?php
namespace App\Criteria;

use Prettus\Repository\Criteria\RequestCriteria as BaseRequestCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use JWTAuth;
use Carbon\Carbon;
use Collator;
use Arr;

/**
 * Class RequestCriteria
 * @package Prettus\Repository\Criteria
 */
class RequestCriteria extends BaseRequestCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    protected $search = [];


    protected $fieldSearchable = [];

    protected $sort = [];

    protected $fields = [];

    public function boot()
    {
        $request = app('request');
        $this->request = $request;

        if($request->has('search')){
            $search = $request->input('search');
            $result = is_array($search) ? $search : $this->parserSearchData($search);
            $this->search = $result;
            $this->fields = Arr::only($result,$this->fieldSearchable);
        }

        if($request->has('sort')){
            $sort = $request->input('sort');
            $this->sort = is_array($sort) ? $sort : $this->parserSearchData($sort);
        }
        return $this;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * Get user
     *
     * @param  void
     * @return User
     */
    public function getUser()
    {
        return JWTAuth::parseToken()->toUser();
    }

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
}
