<?php

namespace App\Common\EventListener;

use App\Common\Response\ForbiddenException;
use App\Common\Response\RouteNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        /** @var NotFoundHttpException|HttpException $exception */
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            throw new RouteNotFoundException();
        }

        if ($exception instanceof AccessDeniedHttpException) {
            throw new ForbiddenException();
        }

        if (!method_exists($exception, 'getStatusCode')) {
            return;
        }

        $event->setResponse(new JsonResponse(
            data: $exception,
            status: $exception->getStatusCode()
        ));
    }
}
