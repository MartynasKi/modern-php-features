<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

$io->title('Reading the final return value');

$fiber = new Fiber(function (): int {
    $total = 20 + 22;

    Fiber::suspend();

    return $total;
});

$fiber->start();

$io->section('While suspended');
dump(['terminated' => $fiber->isTerminated()]);

try {
    $fiber->getReturn();
} catch (FiberError $error) {
    $io->text('getReturn() throws FiberError before the fiber finishes.');
    dump($error->getMessage());
}

$resumeResult = $fiber->resume();

$io->section('After completion');
dump(['terminated' => $fiber->isTerminated()]);

if ($fiber->isTerminated()) {
    // Completing the callback does not send its return value through resume().
    dump([
        'resume()' => $resumeResult,
        'getReturn()' => $fiber->getReturn(),
    ]);
}
