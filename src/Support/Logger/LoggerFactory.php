<?php

namespace App\Support\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LoggerFactory implements LoggerFactoryInterface
{
    private array $settings = [];

    /** @var HandlerInterface[] */
    private array $handler = [];

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function createLogger(string $name = null): LoggerInterface
    {
        $logger = new Logger($name ?? 'app');

        foreach ($this->handler as $handler) {
            $logger->pushHandler($handler);
        }

        $this->handler = [];

        return $logger;
    }

    public function addFile(string $filename): LoggerFactoryInterface
    {
        $path = $this->settings['path'];
        $filename = sprintf('%s/%s', $path, $filename);

        $rotatingFileHandler = new RotatingFileHandler($filename, 0, LogLevel::DEBUG, true, 0777);
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));
        $this->handler[] = $rotatingFileHandler;

        return $this;
    }

    public function addConsole(): LoggerFactoryInterface
    {
        $streamHandler = new StreamHandler('php://output', LogLevel::DEBUG);
        $streamHandler->setFormatter(new LineFormatter(null, null, false, true));
        $this->handler[] = $streamHandler;

        return $this;
    }
}