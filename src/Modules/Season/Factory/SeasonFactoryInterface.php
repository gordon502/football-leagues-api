<?php

namespace App\Modules\Season\Factory;

use App\Modules\Season\Model\SeasonCreatableInterface;
use App\Modules\Season\Model\SeasonInterface;

interface SeasonFactoryInterface
{
    public function create(
        SeasonCreatableInterface $seasonCreatable,
        string $modelClass
    ): SeasonInterface;
}
