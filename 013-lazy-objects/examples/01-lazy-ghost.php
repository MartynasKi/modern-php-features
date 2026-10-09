<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Lazy ghost');

final class User
{
    public string $name;

    public function __construct(
        public int $id,
    ) {
        text("Running a database query for user {$id}");
        $this->name = 'Ann';
    }
}

$reflector = new ReflectionClass(User::class);

// A ghost is the real object. The initializer fills it in later.
$user = $reflector->newLazyGhost(function (User $user): void {
    $user->__construct(42);
});

// ORMs usually know the ID already, so they set it without loading.
$reflector->getProperty('id')->setRawValueWithoutLazyInitialization($user, 42);

section('Created, but no query yet');
dump($reflector->isUninitializedLazyObject($user));

section('Reading the ID does not load it');
dump($user->id);
dump($reflector->isUninitializedLazyObject($user));

section('Reading the name runs the initializer');
dump($user->name);
dump($reflector->isUninitializedLazyObject($user));

section('It is a real User');
dump($user instanceof User);
