<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Generators vs fibers');

// Both tasks want to pause inside a nested call.

function generatorStep(int $number): Generator
{
    yield "Generator step {$number}";
}

function generatorTask(): Generator
{
    // Every layer must be a generator and pass values up with yield from.
    yield from generatorStep(1);
    yield from generatorStep(2);
}

function fiberStep(int $number): void
{
    Fiber::suspend("Fiber step {$number}");
}

function fiberTask(): void
{
    // Plain function calls. The fiber pauses from inside fiberStep().
    fiberStep(1);
    fiberStep(2);
}

section('Generator');
foreach (generatorTask() as $step) {
    text($step);
}

section('Fiber');
$fiber = new Fiber(fiberTask(...));
$step = $fiber->start();

while (! $fiber->isTerminated()) {
    text($step);
    $step = $fiber->resume();
}
