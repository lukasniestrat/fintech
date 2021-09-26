<?php

namespace App\EventListener;

use App\Exception\Common\FinException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JsonExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $httpStatusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $exceptionCode = 'Exception-0';
        $responseData = [
            'success' => false,
            'code' => $exceptionCode,
        ];

        $loggerContext = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'fin' => [
                'code' => $exceptionCode,
                'context' => [],
            ],
        ];

        if ($exception instanceof FinException) {
            $httpStatusCode = $exception->getHttpStatusCode();
            $classArray = explode('\\', get_class($exception));
            $exceptionCode = end($classArray) . '-' . $exception->getType();

            $responseData['code'] = $exceptionCode;
            $responseData['detail'] = $exception->getDetail();

            $loggerContext['fin']['context'] = $exception->getContext();
        }

        $this->logger->log(LogLevel::CRITICAL, $exceptionCode . ' ' . $exception->getMessage(), $loggerContext);

        $response = new JsonResponse($responseData, $httpStatusCode);
        $event->setResponse($response);
    }
}