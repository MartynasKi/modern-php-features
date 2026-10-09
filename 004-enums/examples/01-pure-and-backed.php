<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Pure and backed enums');

// Pure enum
enum Suit
{
    case Hearts;
    case Spades;
}

// Backed enum
enum Status: string
{
    case Draft = 'draft';
    case Published = 'published';
}

section('A pure enum case has only a name');
dump(Suit::Hearts->name);
dump(Suit::cases());

section('A backed enum case also has a value');
dump(Status::Published->name);
dump(Status::Published->value);

section('Each case is a single object');
dump(Suit::Hearts === Suit::Hearts);
dump(Suit::Hearts === Suit::Spades);

section('Built-in interfaces');
dump(Suit::Hearts instanceof UnitEnum);
dump(Suit::Hearts instanceof BackedEnum);
dump(Status::Draft instanceof BackedEnum);
