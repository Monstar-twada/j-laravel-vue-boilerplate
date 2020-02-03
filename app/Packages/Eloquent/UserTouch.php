<?php

namespace App\Packages\Eloquent;
use App\Entities\User;
use Illuminate\Database\Eloquent\Builder;
trait UserTouch
{
    /*
     * Indicates the primary key of the model.
     * */
    protected $id = 'id';

    /*
     * Indicates the class of the user touching the model.
     * */
    protected $user = User::class;

    /**
     * Indicates if the model should be userTouched when updated
     *
     * @var bool
     */
    public $user_touch = true;

    /**
     * Perform a model insert operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    protected function performInsert(Builder $query)
    {
        if($this->usesUserTouch()){
            $this->updateUserTouch();
        }
        parent::performInsert($query);
    }

    /**
     * Update the model's update user touch
     *
     * @return bool
     */
    public function touch()
    {
        if (! $this->usesUserTouch()) {
            return false;
        }

        $this->updateUserTouch();

        return $this->save();
    }

    /**
     * Update the created and updated by
     *
     * @return void
     */
    protected function updateUserTouch()
    {
        $user_id = $this->getUserId();
        $updated_by_column = $this->getUpdatedByColumn();
        $created_by_column = $this->getCreatedByColumn();

        if (! is_null($updated_by_column) && ! $this->isDirty($updated_by_column)) {
            $this->setUpdatedBy($user_id);
        }

        if (! $this->exists && ! is_null($created_by_column) &&
            ! $this->isDirty($created_by_column)) {
            $this->setCreatedBy($user_id);
        }
    }

    /**
     * Set the value of the "created by" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setCreatedBy($value)
    {
        $this->{$this->getCreatedByColumn()} = $value;

        return $this;
    }

    /**
     * Set the value of the "updated by" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setUpdatedBy($value)
    {
        $this->{$this->getUpdatedByColumn()} = $value;

        return $this;
    }

    /**
     * Get the id of current user logged in for the model
     *
     * @return \Illuminate\Support\Carbon
     */
    public function getUserId()
    {
        return $this->getUser()->id;
    }

    /**
     * Get the current user logged in for the model
     *
     * @return string
     */
    public function getUser()
    {
        return Auth::user();
    }

    /**
     * Determine if the model uses user touch
     *
     * @return bool
     */
    public function usesUserTouch()
    {
        return $this->user_touch;
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }

    /**
     * Get the name of the "updated by" column.
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }

    public function creator()
    {
        return $this->user->where('id',$this->created_by);
    }

    public function updator()
    {
        return $this->user->where('id',$this->created_by);
    }

}
