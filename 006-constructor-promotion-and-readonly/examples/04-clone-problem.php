<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Readonly and clone');

readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency,
    ) {}

    // The classic wither: clone, then change one property.
    public function withAmountByClone(int $amount): static
    {
        $copy = clone $this;
        $copy->amount = $amount;

        return $copy;
    }

    // The workaround: build a new object and repeat every argument.
    public function withAmount(int $amount): static
    {
        return new static($amount, $this->currency);
    }
}

$price = new Money(1000, 'EUR');

section('clone copies the initialized readonly property');
try {
    $price->withAmountByClone(500);
} catch (Error $error) {
    dump($error->getMessage());
}

section('new static() works but repeats every argument');
dump($price->withAmount(500));

readonly class Invoice
{
    public function __construct(
        public DateTime $issuedAt,
    ) {}

    // Since PHP 8.3, __clone() may set readonly properties again.
    public function __clone()
    {
        $this->issuedAt = clone $this->issuedAt;
    }
}

section('PHP 8.3: deep clone in __clone()');
$invoice = new Invoice(new DateTime('2026-01-01'));
$copy = clone $invoice;
$copy->issuedAt->modify('+1 month');
dump($invoice->issuedAt->format('Y-m-d'));
dump($copy->issuedAt->format('Y-m-d'));
