<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Pause and resume a fiber');

$fiber = new Fiber(function (): void {
    text('Fiber: started');
    Fiber::suspend();
    text('Fiber: resumed');
});

text('Main: starting the fiber');
$fiber->start();

text('Main: resuming the fiber');
$fiber->resume();

success('The fiber has finished.');
