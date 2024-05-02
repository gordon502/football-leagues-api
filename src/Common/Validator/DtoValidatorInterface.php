<?php

namespace App\Common\Validator;

interface DtoValidatorInterface
{
    public function validate(object $dto, array|null $groups): void;

    public function validatePartial(object $dto, array|null $groups): void;
}
