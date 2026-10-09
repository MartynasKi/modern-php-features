<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Lazy proxy');

final class Mailer
{
    public function __construct(
        public string $host,
    ) {
        text("Connecting to {$host}");
    }

    public function send(string $to): string
    {
        return "Sent to {$to} through {$this->host}";
    }
}

$reflector = new ReflectionClass(Mailer::class);
$real = null;

// A proxy forwards to another instance that the factory creates.
$mailer = $reflector->newLazyProxy(function (Mailer $proxy) use (&$real): Mailer {
    return $real = new Mailer('smtp.example.com');
});

section('No connection yet');
dump($reflector->isUninitializedLazyObject($mailer));

section('The first method call creates the real Mailer');
dump($mailer->send('ann@example.com'));
dump($mailer->send('bob@example.com'));

section('The proxy and the real instance are different objects');
dump($mailer === $real);
dump($mailer->host === $real->host);
