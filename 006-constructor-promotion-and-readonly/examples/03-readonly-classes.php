<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Readonly classes');

// Every property of a readonly class is readonly.
readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency,
    ) {}
}

$price = new Money(1000, 'EUR');

section('Values are set once');
dump($price);

section('No property can change');
try {
    $price->amount = 0;
} catch (Error $error) {
    dump($error->getMessage());
}

section('No dynamic properties either');
try {
    $price->note = 'sale';
} catch (Error $error) {
    dump($error->getMessage());
}
