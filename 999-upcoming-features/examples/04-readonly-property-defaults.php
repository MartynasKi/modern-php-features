<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Readonly property defaults');

final class Connection
{
    // Before PHP 8.6, a readonly property could not have a default value.
    public readonly string $driver = 'mysql';

    public readonly int $port;

    public function __construct(int $port = 3306)
    {
        $this->port = $port;
    }
}

final class OverrideAttempt
{
    public readonly string $driver = 'mysql';

    public function __construct()
    {
        $this->driver = 'pgsql';
    }
}

section('The default counts as the one allowed write');
dump(new Connection());

section('So the constructor cannot change it either');
try {
    new OverrideAttempt();
} catch (Error $error) {
    dump($error->getMessage());
}
