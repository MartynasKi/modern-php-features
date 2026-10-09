<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Readonly properties');

final class User
{
    public function __construct(
        public readonly string $email,
        public readonly ArrayObject $tags,
    ) {}

    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }
}

$user = new User('ann@example.com', new ArrayObject(['php']));

section('Reading is fine');
dump($user->email);

section('Writing from outside throws');
try {
    $user->email = 'bob@example.com';
} catch (Error $error) {
    dump($error->getMessage());
}

section('Writing from inside the class throws too');
try {
    $user->changeEmail('bob@example.com');
} catch (Error $error) {
    dump($error->getMessage());
}

section('A readonly object property can still change inside');
$user->tags[] = 'laravel';
dump($user->tags->getArrayCopy());
