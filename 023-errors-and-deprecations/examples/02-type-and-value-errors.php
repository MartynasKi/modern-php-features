<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('TypeError and ValueError');

$cases = [
    // Wrong type: TypeError.
    'strlen(42)' => fn() => strlen(42),
    'array_sum("1,2")' => fn() => array_sum('1,2'),
    // Too few arguments: ArgumentCountError, a child of TypeError.
    'str_repeat("a")' => fn() => str_repeat('a'),
    // Right type, wrong value: ValueError.
    'str_repeat("a", -1)' => fn() => str_repeat('a', -1),
    'array_chunk([1, 2], 0)' => fn() => array_chunk([1, 2], 0),
    'array_combine(["a"], [1, 2])' => fn() => array_combine(['a'], [1, 2]),
];

foreach ($cases as $code => $case) {
    try {
        $case();
    } catch (TypeError|ValueError $error) {
        section($code);
        text($error::class . ': ' . $error->getMessage());
    }
}
