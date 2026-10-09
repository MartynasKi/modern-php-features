<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Nullsafe operator');

final class Address
{
    public function __construct(
        public string $city,
    ) {}
}

final class User
{
    public function __construct(
        public ?Address $address = null,
    ) {}

    public function address(): ?Address
    {
        return $this->address;
    }
}

function load(string $what): string
{
    text("Loading {$what}");

    return $what;
}

$withAddress = new User(new Address('Vilnius'));
$withoutAddress = new User();
$nobody = null;

section('Before PHP 8.0');
dump($withoutAddress !== null && $withoutAddress->address() !== null ? $withoutAddress->address()->city : null);

section('With ?->');
dump($withAddress->address()?->city);
dump($withoutAddress->address()?->city);
dump($nobody?->address()?->city);

section('The rest of the chain is skipped, arguments too');
dump($nobody?->address(load('arguments')));
