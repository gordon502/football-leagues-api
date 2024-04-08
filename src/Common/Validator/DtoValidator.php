<?php

namespace App\Common\Validator;

use App\Common\Response\UnprocessableEntityException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class DtoValidator implements DtoValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(object $dto): void
    {
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            throw new UnprocessableEntityException($this->buildMessage($violations));
        }
    }

    private function buildMessage(ConstraintViolationListInterface $violations): string
    {
        $message = '';
        foreach ($violations as $violation) {
            $message .= $violation->getPropertyPath() . ': ' . $violation->getMessage() . PHP_EOL;
        }

        return $message;
    }
}
