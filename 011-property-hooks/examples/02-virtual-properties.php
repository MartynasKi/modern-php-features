<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Virtual properties');

final class Rectangle
{
    public function __construct(
        public int $width,
        public int $height,
    ) {}

    // No backing value. It is computed on every read.
    public int $area {
        get => $this->width * $this->height;
    }

    // A virtual property can have a set hook that updates real properties.
    public string $size {
        get => "{$this->width}x{$this->height}";
        set(string $value) {
            [$this->width, $this->height] = array_map(intval(...), explode('x', $value));
        }
    }
}

$rectangle = new Rectangle(3, 4);

section('Computed on read');
dump($rectangle->area);
$rectangle->width = 10;
dump($rectangle->area);

section('Writing to a get-only virtual property throws');
try {
    $rectangle->area = 100;
} catch (Error $error) {
    dump($error->getMessage());
}

section('A set hook can update other properties');
$rectangle->size = '5x6';
dump($rectangle->width);
dump($rectangle->height);

section('An array cast only shows the stored properties');
dump((array) $rectangle);
