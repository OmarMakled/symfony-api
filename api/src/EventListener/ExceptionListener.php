<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionListener
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse(
            [
                'error' => $this->translator->trans($exception->getMessage(), [], 'messages')
            ],
            $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500
        );
        $event->setResponse($response);
    }
}
