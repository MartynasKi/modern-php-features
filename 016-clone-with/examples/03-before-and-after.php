<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Withers before and after PHP 8.5');

// Before PHP 8.5: repeat every argument in each wither.
readonly class OldAddress
{
    public function __construct(
        public string $street,
        public string $city,
        public string $country,
    ) {}

    public function withCity(string $city): static
    {
        return new static($this->street, $city, $this->country);
    }
}

// PHP 8.5: name only the property that changes.
readonly class Address
{
    public function __construct(
        public string $street,
        public string $city,
        public string $country,
    ) {}

    public function withCity(string $city): static
    {
        return clone($this, ['city' => $city]);
    }
}

section('Before');
dump(new OldAddress('Gedimino 1', 'Vilnius', 'LT')->withCity('Kaunas'));

section('After');
dump(new Address('Gedimino 1', 'Vilnius', 'LT')->withCity('Kaunas'));
