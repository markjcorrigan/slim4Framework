<?php

namespace App\Middleware;

use App\Support\Logger\LoggerFactoryInterface;
use ErrorException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    private LoggerInterface $logger;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        LoggerFactoryInterface $loggerFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->logger = $loggerFactory
            ->addFile('error.log')
            ->createLogger('error_handler_middleware');
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            $this->setErrorHandler();

            return $handler->handle($request);
        } catch (ErrorException $exception) {
            $this->logErrorException($exception);

            // Render the response as you need it, for example a JSON response etc.
            $response = $this->responseFactory->createResponse(500);
            $response->getBody()->write('Internal Server Error');

            return $response;
        }
    }

    private function setErrorHandler(): void
    {
        $errorLevels = E_ALL;

        // Set custom php error handler
        set_error_handler(
            function ($errorNumber, $errorMessage, $errorFile, $errorLine) {
                throw new ErrorException($errorMessage, $errorNumber, 1, $errorFile, $errorLine);
            },
            $errorLevels
        );
    }

    private function logErrorException(ErrorException $exception): void
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $line = $exception->getLine();
        $file = $exception->getFile();
        $errorMessage = sprintf('Error number [%s] %s on line %s in file %s', $code, $message, $line, $file);

        switch ($exception->getCode()) {
            case E_USER_ERROR:
                $this->logger->error($errorMessage);
                break;
            case E_USER_WARNING:
                $this->logger->warning($errorMessage);
                break;
            default:
                $this->logger->notice($errorMessage);
        }
    }
}