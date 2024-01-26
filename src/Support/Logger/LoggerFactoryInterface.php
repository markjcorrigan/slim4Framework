<?php

namespace App\Support\Logger;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{
    public function createLogger(string $name = null): LoggerInterface;

    public function addFile(string $filename): LoggerFactoryInterface;

    public function addConsole(): LoggerFactoryInterface;
}
