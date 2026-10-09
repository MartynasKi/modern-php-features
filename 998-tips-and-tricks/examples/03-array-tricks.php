<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Array tricks');

section('Swap two variables without a temporary one');
$first = 'left';
$second = 'right';
[$first, $second] = [$second, $first];
dump([$first, $second]);

section('Destructure by key, even in foreach');
$users = [
    ['id' => 1, 'name' => 'Ann', 'role' => 'admin'],
    ['id' => 2, 'name' => 'Bob', 'role' => 'member'],
];

foreach ($users as ['name' => $name, 'role' => $role]) {
    text("{$name}: {$role}");
}

section('Spread arrays with string keys (PHP 8.1)');
$defaults = ['theme' => 'light', 'language' => 'en'];
$custom = ['theme' => 'dark'];
dump([...$defaults, ...$custom]);

section('array_column() can index by another column');
dump(array_column($users, 'name', 'id'));

section('array_map(null, ...) zips arrays together');
dump(array_map(null, ['a', 'b'], [1, 2]));
