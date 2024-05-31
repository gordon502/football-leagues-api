<?php

namespace App\Modules\User\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class UserEmailIsAlreadyTakenException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'User with this email is already taken.'
    ) {
        parent::__construct(
            statusCode: HttpCode::CONFLICT,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'USER_EMAIL_IS_ALREADY_TAKEN',
            'message' => $this->message,
        ];
    }
}
