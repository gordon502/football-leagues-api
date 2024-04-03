<?php

namespace App\Common\Model;

interface SoftDeletableModelInterface
{
    public function getDeletedAt();
}
