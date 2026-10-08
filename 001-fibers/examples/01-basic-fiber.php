<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('A fiber from start to finish');

$fiber = new Fiber(function (): void {
    $message = 'This local value survives the pause.';

    text('Fiber: started. Pausing here.');
    Fiber::suspend();

    text('Fiber: resumed.');
    dump($message);
});

section('Before start');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);

text('Main: starting the fiber.');
$fiber->start();

section('After suspension');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);

text('Main: resuming the fiber.');
$fiber->resume();

section('After completion');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);
