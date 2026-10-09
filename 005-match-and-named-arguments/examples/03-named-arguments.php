<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Named arguments');

function createUser(
    string $name,
    string $role = 'member',
    bool $active = true,
    bool $sendWelcomeEmail = true,
): array {
    return compact('name', 'role', 'active', 'sendWelcomeEmail');
}

section('Positional: what do true and false mean?');
dump(createUser('Ann', 'member', true, false));

section('Named: skip the defaults you do not change');
dump(createUser('Ann', sendWelcomeEmail: false));

section('Order does not matter');
dump(createUser(role: 'admin', name: 'Bob'));

section('Works with built-in functions too');
dump(htmlspecialchars('&amp; <b>', double_encode: false));
dump(str_pad('7', 3, '0', pad_type: STR_PAD_LEFT));

section('Spread a string-keyed array as named arguments');
$options = ['name' => 'Cid', 'active' => false];
dump(createUser(...$options));

section('An unknown name throws an Error');
try {
    createUser('Dan', admin: true);
} catch (Error $error) {
    dump($error->getMessage());
}
