<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Read the return value');

$fiber = new Fiber(function (): int {
    Fiber::suspend();

    return 42;
});

$fiber->start();
$fiber->resume();

section('getReturn()');
dump($fiber->getReturn());
