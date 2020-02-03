<?php

namespace App\Packages\Parsers;
use Illuminate\Http\Request;

abstract class AbstractParser
{

    abstract public function parse($val);
}
