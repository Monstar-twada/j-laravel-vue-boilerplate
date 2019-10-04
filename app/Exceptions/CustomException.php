<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    //
    protected $type = "CustomException";

    protected $action = "";

    public function __construct(string $message, string $action = "", int $code = 0, Throwable $previous = null)
    {
        $this->boot();
        $this->action = $action;

        parent::__construct($message, $code, $previous);
    }

    public function boot()
    { }

    public function getType()
    {
        return $this->type;
    }

    public function getAction()
    {
        return $this->action;
    }
}
