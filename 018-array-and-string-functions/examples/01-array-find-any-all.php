<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('array_find, array_any and array_all');

$users = [
    'ann' => ['age' => 34, 'admin' => true],
    'bob' => ['age' => 17, 'admin' => false],
    'cid' => ['age' => 25, 'admin' => false],
];

$isAdult = fn(array $user) => $user['age'] >= 18;

section('array_find() returns the first match');
dump(array_find($users, fn(array $user) => ! $user['admin']));

section('array_find_key() returns its key');
dump(array_find_key($users, fn(array $user) => ! $user['admin']));

section('No match gives null');
dump(array_find($users, fn(array $user) => $user['age'] > 100));

section('array_any(): is at least one user an adult?');
dump(array_any($users, $isAdult));

section('array_all(): are all users adults?');
dump(array_all($users, $isAdult));

section('The callback also gets the key');
dump(array_find_key($users, fn(array $user, string $name) => str_starts_with($name, 'c')));

section('Before PHP 8.4');
dump(array_values(array_filter($users, fn(array $user) => ! $user['admin']))[0] ?? null);
dump(count(array_filter($users, $isAdult)) > 0);
