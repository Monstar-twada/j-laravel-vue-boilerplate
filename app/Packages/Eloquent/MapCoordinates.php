<?php

namespace App\Packages\Eloquent;
use Illuminate\Database\Eloquent\Builder;

trait MapCoordinates
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $coordinates = true;

    /**
     * Update the model's longitude and latitude coordinates.
     * when there's changes
     *
     * @return bool
     */
    public function touch()
    {
        if (! $this->usesMapCoordinates()) {
            return false;
        }
        $this->updateCoordinates();
        return $this->save();
    }

    /**
     * Perform a model insert operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    protected function performInsert(Builder $query)
    {
        if($this->usesMapCoordinates()){
            $this->updateCoordinates();
        }
        parent::performInsert($query);
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return void
     */
    protected function updateCoordinates()
    {
        $this->setLatitude($this->getLatitudeCoordinate());
        $this->setLongitude($this->getLongitudeCoordinate());
    }

    /**
     * Set the value of the "longitude" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setLongitude($value)
    {
        $this->{$this->getLongitudeColumn()} = $value;

        return $this;
    }

    /**
     * Set the value of the "latitude" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setLatitude($value)
    {
        $this->{$this->getLatitudeColumn()} = $value;

        return $this;
    }

    /**
     * Determine if the model uses timestamps.
     *
     * @return bool
     */
    public function usesMapCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Get the name of the "longitude" column.
     *
     * @return string
     */
    public function getLongitudeColumn()
    {
        return defined('static::LONGITUDE') ? static::LONGITUDE : 'longitude';
    }

    /**
     * Get the name of the "latitude" column.
     *
     * @return string
     */
    public function getLatitudeColumn()
    {
        return defined('static::LATITUDE') ? static::LATITUDE : 'latitude';
    }

    public function getLongitudeCoordinate()
    {
        return 0;
    }

    public function getLatitudeCoordinate()
    {
        return 0;
    }

}
