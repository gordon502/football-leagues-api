<?php

namespace App\Common\AutowireFactory\Exception;

class FolderParameterMissingException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Folder parameter {DB_IMPL} in provided namespace is missing.'
        );
    }
}
