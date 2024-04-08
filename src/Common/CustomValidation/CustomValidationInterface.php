<?php

namespace App\Common\CustomValidation;

interface CustomValidationInterface
{
    public function validate($value): void;
}
