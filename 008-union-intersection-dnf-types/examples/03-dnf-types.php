<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('DNF types');

// An intersection in parentheses, joined with a union.
function countOrNothing((Countable&Traversable)|null $items): string
{
    return $items === null ? 'nothing' : count($items) . ' items';
}

section('Countable and Traversable');
dump(countOrNothing(new ArrayIterator([1, 2, 3])));

section('null');
dump(countOrNothing(null));

section('Only Countable is not enough');
$onlyCountable = new class implements Countable {
    public function count(): int
    {
        return 1;
    }
};

try {
    countOrNothing($onlyCountable);
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}
