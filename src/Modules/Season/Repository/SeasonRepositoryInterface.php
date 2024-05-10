<?php

namespace App\Modules\Season\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;

interface SeasonRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
}
