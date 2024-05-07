<?php

namespace App\Common\Validator;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Response\UnprocessableEntityException;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class DtoValidator implements DtoValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(object $dto, array|null $groups = null): void
    {
        $violations = $this->validator->validate(
            value: $dto,
            groups: $groups,
        );

        if (count($violations) > 0) {
            throw new UnprocessableEntityException($this->buildMessage($violations));
        }
    }

    public function validatePartial(object $dto, array|null $groups = null): void
    {
        $reflection = new ReflectionClass($dto);
        /** @var ConstraintViolationListInterface $violations */
        $violations = null;

        foreach ($reflection->getProperties() as $property) {
            $propertyNameUcFirst = ucfirst($property->getName());
            $value = $property->getValue($dto);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            $getIsMethod = $reflection->hasMethod('get' . $propertyNameUcFirst)
                ? 'get' . $propertyNameUcFirst
                : ($reflection->hasMethod('is' . $propertyNameUcFirst)
                    ? 'is' . $propertyNameUcFirst
                    : null);

            if ($getIsMethod === null) {
                throw new RuntimeException('Getter or Is method not found for property ' . $property->getName());
            }

            $validated = $this->validator->validateProperty(
                object: $dto,
                propertyName: $getIsMethod,
                groups: $groups
            );

            if ($validated->count() === 0) {
                continue;
            }

            if ($violations === null) {
                $violations = $validated;
                continue;
            }

            $violations->addAll($validated);
        }

        if ($violations !== null && $violations->count() > 0) {
            throw new UnprocessableEntityException($this->buildMessage($violations));
        }
    }

    private function buildMessage(ConstraintViolationListInterface $violations): string
    {
        $message = '';
        foreach ($violations as $violation) {
            $message .= $this->camelCaseToSnakeCase($violation->getPropertyPath()) . ': ' . $violation->getMessage() . PHP_EOL;
        }

        return $message;
    }

    private function camelCaseToSnakeCase(string $str): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }
}
