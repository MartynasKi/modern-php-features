<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Withers on readonly classes');

readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency,
    ) {}

    public function withAmount(int $amount): static
    {
        return clone($this, ['amount' => $amount]);
    }

    public function withCurrency(string $currency): static
    {
        return clone($this, ['currency' => $currency]);
    }
}

$price = new Money(1000, 'EUR');

section('Each wither returns a changed copy');
dump($price->withAmount(1500)->withCurrency('USD'));
dump($price);

section('From outside, readonly properties are protected(set)');
try {
    clone($price, ['amount' => 1]);
} catch (Error $error) {
    dump($error->getMessage());
}
