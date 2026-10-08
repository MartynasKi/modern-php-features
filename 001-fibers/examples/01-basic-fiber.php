<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

$io->title('A fiber from start to finish');

$fiber = new Fiber(function () use ($io): void {
    $message = 'This local value survives the pause.';

    $io->text('Fiber: started. Pausing here.');
    Fiber::suspend();

    $io->text('Fiber: resumed.');
    dump($message);
});

$io->section('Before start');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);

$io->text('Main: starting the fiber.');
$fiber->start();

$io->section('After suspension');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);

$io->text('Main: resuming the fiber.');
$fiber->resume();

$io->section('After completion');
dump([
    'started' => $fiber->isStarted(),
    'suspended' => $fiber->isSuspended(),
    'terminated' => $fiber->isTerminated(),
]);
