<?php

namespace App\Modules\Auth\EventListener;

use App\Common\Response\ErrorResponse;
use App\Common\Response\HttpCode;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtAuthenticationFailureListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $error = new ErrorResponse(
            HttpCode::UNAUTHORIZED,
            'INVALID_CREDENTIALS',
            'Invalid credentials, could not log you in.'
        );

        $response = new JsonResponse($error, $error->code);

        $event->setResponse($response);
    }

    public function onJwtNotFound(JWTNotFoundEvent $event): void
    {
        $error = new ErrorResponse(
            HttpCode::UNAUTHORIZED,
            'JWT_NOT_FOUND',
            'JWT token not found.'
        );

        $response = new JsonResponse($error, $error->code);

        $event->setResponse($response);
    }
}
