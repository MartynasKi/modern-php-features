<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('new in initializers');

interface Logger
{
    public function log(string $message): void;
}

final class NullLogger implements Logger
{
    public function log(string $message): void {}
}

final class EchoLogger implements Logger
{
    public function log(string $message): void
    {
        text("LOG: {$message}");
    }
}

final class Mailer
{
    // Before PHP 8.1: ?Logger $logger = null and then $logger ?? new NullLogger().
    public function __construct(
        private Logger $logger = new NullLogger(),
    ) {}

    public function send(string $to): void
    {
        $this->logger->log("Mail sent to {$to}");
    }
}

section('The default object is created only when needed');
new Mailer()->send('ann@example.com');
new Mailer(new EchoLogger())->send('bob@example.com');

section('Also works for static variables and global constants');
const DEFAULT_LOGGER = new EchoLogger();

function logOnce(): Logger
{
    static $logger = new EchoLogger();

    return $logger;
}

DEFAULT_LOGGER->log('From a constant');
logOnce()->log('From a static variable');
