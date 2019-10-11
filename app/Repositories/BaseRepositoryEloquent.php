<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Validators\UserValidator;
use App\Validators\SalesmanValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class BaseRepositoryEloquent extends BaseRepository
{
    public $primaryKey = 'id';
    public $method_scope = "";

    public function model(){

    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }

    public function count(array $where = [], $column='*')
    {
        $this->method_scope="count";
        $this->applyCriteria();
        $this->applyScope();

        if($where) {
            $this->applyConditions($where);
        }
        $result = 0;

        //$result = $this->model->count(!$column ? $this->primaryKey : $column);
        $result = $this->model->count('*');
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $request = app('request')->request;
        $total = $request->has('total') ? $request->get('total') : $this->count();

        $this->method_scope="listing";

        $page = 1;
        if($request->has('page')){
            $page = $request->get('page');
        }

        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $start = ( $page - 1 ) * $limit;
        $end = $start + ( $limit > 1 ? $limit - 1 : $limit);
        if($end > $total){
            $end = $total;
        }
        if(!$request->has('search') && !$request->has('sort')){
                $this->scopeQuery(function($query) use($start,$end){
                    return $query->whereBetween($this->primaryKey,[$start,$end]);
                    //return $query->where('id','>=',$start)->where('id','<=',$end);
                });
        }else{
            $this->scopeQuery(function($query) use($start,$end,$limit){
                return $query->skip($start)->take($limit)->limit($limit);
            });
        }

        //$parameters = $request->getQueryString();
        //$parameters = preg_replace('/&page(=[^&]*)?|^page(=[^&]*)?&?/','', $parameters);
        //$path = url('/') . '/categories?' . $parameters;

        $this->applyCriteria();
        $this->applyScope();

        $data = $this->model->get($columns);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, $total, $limit, $page);
        //$paginator = $paginator->withPath($path);

        $this->resetModel();


        return $this->parserResult($paginator);
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $this->method_scope="detail";
        return parent::find($id,$columns);
    }

    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        $this->method_scope="detail";
        return parent::find($columns);
    }

    /**
     * Update or Create an entity in repository
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     */
    public function updateOrCreate(array $where, array $attributes = [])
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->updateOrCreate($where, $attributes);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    public function applyCriteria()
    {

        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria) {
            foreach ($criteria as $c) {
                if ($c instanceof CriteriaInterface) {
                    if(method_exists($c,'boot')){
                        $this->model = $c->boot()->apply($this->model, $this);
                    }else{
                        $this->model = $c->apply($this->model, $this);
                    }
                }
            }
        }

        return $this;

    }

}
