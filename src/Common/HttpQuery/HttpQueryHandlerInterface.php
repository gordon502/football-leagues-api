<?php

namespace App\Common\HttpQuery;

use Symfony\Component\HttpFoundation\InputBag;

interface HttpQueryHandlerInterface
{
    public function handle(InputBag $filterQuery, string $testedInterface): HttpQuery;
}
