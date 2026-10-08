<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Static variables');

function counter(): int
{
    static $count = 0;

    return ++$count;
}

section('Calling counter() three times');
dump([counter(), counter(), counter()]);
