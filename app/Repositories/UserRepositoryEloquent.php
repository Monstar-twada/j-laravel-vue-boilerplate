<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\UserCriteria;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Validators\UserValidator;
use Prettus\Repository\Helpers\CacheKeys;
use Illuminate\Support\Str;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{ 
    public function validator()
    {
        return SalesmanValidator::class;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(UserCriteria::class));
    }
    
    public function getUserCacheKey($args)
    {
        $method = "auth-user";
        $args = serialize($args);
        $key = sprintf('%s@%s-%s', get_called_class(), $method,md5($args));
        CacheKeys::putKey(get_called_class(), $key);
        return $key;

    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->model;
        if (!$this->isSkippedCache()) {
            $key = $this->getUserCacheKey(func_get_args());
            $minutes = $this->getCacheMinutes();
            $result = $this->getCacheRepository()->remember($key, $minutes, function () use ($identifier,$model) {
                return $model->where($model->getAuthIdentifierName(), $identifier)->first();
            });
        }else{
            $result = $model->where($model->getAuthIdentifierName(), $identifier)->first();
        }

        $this->resetModel();
        $this->resetScope();
        return $result;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return null;
        }
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        if (!$this->isSkippedCache()) {
            $key = $this->getUserCacheKey(func_get_args());
            $minutes = $this->getCacheMinutes();
            $result = $this->getCacheRepository()->remember($key, $minutes,function()use($credentials){
                return $this->findByCredentials($credentials);
            });
        }else{
            $result = $this->findByCredentials($credentials);
        }

        $this->resetModel();
        $this->resetScope();
        return $result;
    }

    public function findByCredentials(array $credentials){
        $query = $this->model;
        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $query = $query->whereIn($key, $value);
            } else {
                $query = $query->where($key, $value);
            }
        }
        return $query->first();
    }

}
