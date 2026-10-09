<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Send values into a generator');

function runningTotal(): Generator
{
    $total = 0;

    while (true) {
        // yield sends $total out and receives the value passed to send().
        $amount = yield $total;
        $total += $amount;
    }
}

$total = runningTotal();

section('current() runs to the first yield');
dump($total->current());

section('send() resumes and returns the next yielded value');
dump($total->send(10));
dump($total->send(5));
dump($total->send(20));
