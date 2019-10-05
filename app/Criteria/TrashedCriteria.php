<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TrashedCriteria.
 *
 * @package namespace App\Criteria;
 */
class TrashedCriteria implements CriteriaInterface
{
    protected $withTrashed = false;

    public function __construct($withTrashed=false)
    {
        $this->withTrashed = $withTrashed;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if($this->withTrashed){
            return $model->withTrashed();
        }
        return $model->withoutTrashed();
    }

}
