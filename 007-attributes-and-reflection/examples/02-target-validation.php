<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Attributes are checked late');

#[Attribute(Attribute::TARGET_METHOD)]
final class Route
{
    public function __construct(
        public string $path,
    ) {}
}

// Route only allows methods, but PHP does not complain here.
#[Route('/users')]
final class UserController {}

#[MissingAttribute]
final class PostController {}

section('Reading the arguments works');
$attribute = (new ReflectionClass(UserController::class))->getAttributes()[0];
dump($attribute->getArguments());

section('newInstance() checks the target');
try {
    $attribute->newInstance();
} catch (Error $error) {
    dump($error->getMessage());
}

section('newInstance() needs the class to exist');
try {
    (new ReflectionClass(PostController::class))->getAttributes()[0]->newInstance();
} catch (Error $error) {
    dump($error->getMessage());
}
