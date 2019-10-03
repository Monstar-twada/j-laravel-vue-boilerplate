<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Helpers\CacheKeys;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CacheableRepositoryEloquent extends BaseRepositoryEloquent implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Retrieve all data of repository, countd
     *
     * @param null  $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function count(array $where = [], $column='*')
    {
        if (!$this->allowedCache('count') || $this->isSkippedCache()) {
            return parent::count($where, $column);
        }

        $key = $this->getCacheKeyBySearch('count', func_get_args());

        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($where, $column) {
            return parent::count($where, $column);
        });

        return $value;
    }

    public function getCacheKeyBySearch($method,$args = null)
    {

        $request = app('Illuminate\Http\Request');
        $args = serialize($args);
        $criteria = $this->serializeCriteria();
        $url = $request->url() . '?search=' . $request->input('search');

        $key = sprintf('%s@%s-%s', get_called_class(), $method, md5($args . $criteria . $url));

        CacheKeys::putKey(get_called_class(), $key);

        return $key;

    }
}
