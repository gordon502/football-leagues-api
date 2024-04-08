<?php

namespace App\Common\Validator;

interface DtoValidatorInterface
{
    public function validate(object $dto): void;
}
