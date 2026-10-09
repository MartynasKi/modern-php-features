<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Send values into a generator');

function runningTotal(): Generator
{
    $total = 0;

    while (true) {
        // yield works both ways. It sends $total out and pauses here.
        // On resume, the value passed to send() comes back in as $amount.
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
