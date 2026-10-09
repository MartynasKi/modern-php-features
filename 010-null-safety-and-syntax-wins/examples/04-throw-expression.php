<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('throw as an expression');

function env(array $config, string $key): string
{
    return $config[$key] ?? throw new InvalidArgumentException("Missing {$key}");
}

$config = ['APP_NAME' => 'Modern PHP'];

section('With ??');
dump(env($config, 'APP_NAME'));

try {
    env($config, 'APP_KEY');
} catch (InvalidArgumentException $exception) {
    dump($exception->getMessage());
}

section('With ?:');
$name = '';

try {
    $name ?: throw new LengthException('Name is empty');
} catch (LengthException $exception) {
    dump($exception->getMessage());
}

section('In an arrow function');
$notImplemented = fn() => throw new LogicException('Not implemented');

try {
    $notImplemented();
} catch (LogicException $exception) {
    dump($exception->getMessage());
}
