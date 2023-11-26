<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            $this->getExceptionCode($event)
        );
        $event->setResponse($response);
    }

    private function getExceptionCode(ExceptionEvent $event): int
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            return Response::HTTP_FORBIDDEN;
        }
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
