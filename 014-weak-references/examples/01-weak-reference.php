<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('WeakReference');

final class Connection
{
    public function __destruct()
    {
        text('Connection destroyed');
    }
}

$connection = new Connection();
$weak = WeakReference::create($connection);

section('While a normal variable holds the object');
dump($weak->get() === $connection);

section('A strong copy keeps the object alive');
$strong = $connection;
unset($connection);
dump($weak->get() !== null);

section('Unset the last strong reference');
unset($strong);
dump($weak->get());
