<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Union types');

function findUser(int|string $id): string
{
    return is_int($id) ? "User by id {$id}" : "User by slug {$id}";
}

section('Either type is accepted');
dump(findUser(42));
dump(findUser('ann'));

section('Anything else throws a TypeError');
try {
    findUser(4.2);
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}

function half(int|float $number): int|float
{
    return $number / 2;
}

section('int|float keeps the type you pass');
dump(half(10));
dump(half(5));
