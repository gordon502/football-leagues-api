<?php

namespace App\Modules\Team\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;

interface TeamRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
}
