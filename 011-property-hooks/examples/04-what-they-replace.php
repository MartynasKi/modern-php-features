<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('What hooks replace');

// 1. Getters and setters: every caller must use methods.
final class ProductWithMethods
{
    private int $price = 0;

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = max(0, $price);
    }
}

// 2. Magic __get and __set: property syntax, but no types and one method for all.
final class ProductWithMagic
{
    private array $data = ['price' => 0];

    public function __get(string $name): mixed
    {
        return $this->data[$name];
    }

    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $name === 'price' ? max(0, $value) : $value;
    }
}

// 3. A hook: property syntax, a real typed property and logic in one place.
final class ProductWithHook
{
    public int $price = 0 {
        set => max(0, $value);
    }
}

section('Getter and setter');
$withMethods = new ProductWithMethods();
$withMethods->setPrice(-5);
dump($withMethods->getPrice());

section('__set accepts any type');
$withMagic = new ProductWithMagic();
$withMagic->price = '10';
dump($withMagic->price);

section('A hook keeps the type');
$withHook = new ProductWithHook();
$withHook->price = -5;
dump($withHook->price);

try {
    $withHook->price = '10';
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}
