<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('new without extra parentheses');

final class Greeter
{
    public const DEFAULT_NAME = 'world';

    public string $greeting = 'Hello';

    public function greet(string $name = self::DEFAULT_NAME): string
    {
        return "{$this->greeting}, {$name}!";
    }
}

section('Before PHP 8.4');
dump((new Greeter())->greet());

section('Since PHP 8.4');
dump(new Greeter()->greet('Ann'));
dump(new Greeter()->greeting);
dump(new Greeter()::DEFAULT_NAME);

section('Anonymous classes too');
dump(new class {
    public function hello(): string
    {
        return 'Hi from an anonymous class';
    }
}->hello());
