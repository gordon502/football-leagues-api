<?php

namespace App\Modules\League\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;

interface LeagueRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
}
