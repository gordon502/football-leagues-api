<?php

namespace App\Modules\Round\Factory;

use App\Modules\Round\Model\RoundCreatableInterface;
use App\Modules\Round\Model\RoundInterface;

interface RoundFactoryInterface
{
    public function create(
        RoundCreatableInterface $roundCreatable,
        string $modelClass
    ): RoundInterface;
}
