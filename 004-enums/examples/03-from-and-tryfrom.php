<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('from() vs tryFrom()');

enum Status: string
{
    case Draft = 'draft';
    case Published = 'published';
}

enum Priority: int
{
    case Low = 1;
    case High = 2;
}

section('from() with a valid value');
dump(Status::from('published'));

section('tryFrom() returns null for an unknown value');
dump(Status::tryFrom('deleted'));
dump(Status::tryFrom('deleted') ?? Status::Draft);

section('from() throws ValueError for an unknown value');
try {
    Status::from('deleted');
} catch (ValueError $error) {
    dump($error->getMessage());
}

section('Strict types also apply to the backing type');
try {
    Priority::from('1');
} catch (TypeError $error) {
    dump($error->getMessage());
}
