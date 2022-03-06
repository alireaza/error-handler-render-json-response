<?php

namespace AliReaza\ErrorHandler\Render;

use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class JsonResponse
{
    public function __invoke(?array $errors, Throwable $exception): void
    {
        if (!interface_exists(HttpExceptionInterface::class)) {
            throw new LogicException(sprintf('You cannot use "%s" as the "%s" is not installed. Try running "composer require "%s"".', __CLASS__, 'HttpKernel component', 'symfony/http-kernel'));
        }

        if (!class_exists(SymfonyJsonResponse::class)) {
            throw new LogicException(sprintf('You cannot use "%s" as the "%s" is not installed. Try running "composer require "%s"".', __CLASS__, 'HttpFoundation component', 'symfony/http-foundation'));
        }

        $data = json_decode($exception->getMessage());
        if (JSON_ERROR_NONE !== json_last_error()) {
            $data = $exception->getMessage();
        }

        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() >= 100 && $exception->getStatusCode() < 600) {
            $status_code = $exception->getStatusCode();

            if ($status_code >= 400 && $status_code < 500) {
                $data = [
                    'message' => 'The given data was invalid.',
                    'errors' => $data,
                ];
            }
        } else {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;

            $data = [
                'message' => 'Whoops, looks like something went wrong.',
            ];

            if (is_array($errors)) {
                $data['errors'] = [$errors];
            }
        }

        $response = new SymfonyJsonResponse();
        $response->setStatusCode($status_code);
        $response->setData($data);
        $response->send();
    }
}
