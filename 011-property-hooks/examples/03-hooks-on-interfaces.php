<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Properties on interfaces');

interface Shape
{
    // Every Shape must have a readable $name and $area.
    public string $name { get; }

    public float $area { get; }
}

final class Square implements Shape
{
    // A plain public property fulfils "get".
    public string $name = 'square';

    public function __construct(
        private float $side,
    ) {}

    public float $area {
        get => $this->side ** 2;
    }
}

final class Circle implements Shape
{
    public string $name {
        get => 'circle';
    }

    public function __construct(
        private float $radius,
    ) {}

    public float $area {
        get => round(M_PI * $this->radius ** 2, 2);
    }
}

section('Code can depend on properties of an interface');
foreach ([new Square(3), new Circle(1)] as $shape) {
    text("{$shape->name}: {$shape->area}");
}
