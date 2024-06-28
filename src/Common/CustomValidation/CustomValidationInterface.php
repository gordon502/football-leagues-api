<?php

namespace App\Common\CustomValidation;

interface CustomValidationInterface
{
    public function validate($value, array $customOptions = []): void;
}
