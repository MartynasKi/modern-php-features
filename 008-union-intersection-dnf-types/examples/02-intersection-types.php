<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Intersection types');

// The value must be countable and iterable at the same time.
function summary(Countable&Traversable $items): string
{
    $names = [];

    foreach ($items as $item) {
        $names[] = $item;
    }

    return count($items) . ' items: ' . implode(', ', $names);
}

section('ArrayIterator implements both interfaces');
dump(summary(new ArrayIterator(['a', 'b', 'c'])));

section('ArrayObject too');
dump(summary(new ArrayObject(['x', 'y'])));

section('A plain array is neither');
try {
    summary(['a', 'b']);
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}

section('A generator is Traversable but not Countable');
try {
    summary((fn() => yield 'a')());
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}
