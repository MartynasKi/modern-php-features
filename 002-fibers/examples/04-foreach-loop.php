<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Run fibers in a foreach loop');

$tasks = [
    'Task A' => [1, 2, 3],
    'Task B' => [1, 2, 3, 4, 5],
];

$callback = function (string $name, array $steps): void {
    foreach ($steps as $step) {
        Fiber::suspend("{$name}: step {$step}");
    }
};

$fibers = [];

foreach ($tasks as $name => $steps) {
    $fibers[$name] = new Fiber($callback);

    // start() returns the first value passed to suspend().
    $value = $fibers[$name]->start($name, $steps);
    text("Main received {$value}");
}

while ($fibers !== []) {
    foreach ($fibers as $name => $fiber) {
        // resume() returns the next value, or null when the fiber finishes.
        $value = $fiber->resume();

        if ($fiber->isTerminated()) {
            unset($fibers[$name]);

            continue;
        }

        text("Main received {$value}");
    }
}

success('Both tasks have finished.');
