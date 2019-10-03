<?php

namespace App\Packages\Database\Schema;

use Illuminate\Database\Schema\Blueprint;
use Closure;
use BadMethodCallException;
use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Grammars\Grammar;

class ExtendedBlueprint extends Blueprint
{
    /**
     * Indicate that the user touch columns should be dropped.
     *
     * @return void
     */
    public function dropUserTouches()
    {
        $this->dropColumn('created_by', 'updated_by');
    }

    /**
     * Indicate that the map coordinates columns should be dropped.
     *
     * @return void
     */
    public function dropMapCoordinates()
    {
        $this->dropColumn('latitude', 'longtitude');
    }

    /**
     * Add nullable updated_by and created_by user touches to the table.
     *
     * @param  int  $precision
     * @return void
     */
    public function UserTouches()
    {
        $this->integer('created_by')->unsigned()->nullable();
        $this->integer('updated_by')->unsigned()->nullable();
    }

    public function MapCoordinates()
    {
        $this->decimal('latitude',10,8)->nullable();
        $this->decimal('longitude',11,8)->nullable();
    }


}
