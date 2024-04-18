<?php

namespace App\Common\Model;

interface DbIdModelInterface
{
    public function getDbId(): string|int;
}
