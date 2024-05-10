<?php

namespace App\Modules\Team\Factory;

use App\Modules\Team\Model\TeamCreatableInterface;
use App\Modules\Team\Model\TeamInterface;

interface TeamFactoryInterface
{
    public function create(
        TeamCreatableInterface $teamCreatable,
        string $modelClass
    ): TeamInterface;
}
