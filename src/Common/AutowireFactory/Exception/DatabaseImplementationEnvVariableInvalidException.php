<?php

namespace App\Common\AutowireFactory\Exception;

class DatabaseImplementationEnvVariableInvalidException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            message: 'DATABASE_IMPLEMENTATION env variable is invalid or missing. Valid values are: "InMemory", "MySQL", "MongoDB".'
        );
    }
}
