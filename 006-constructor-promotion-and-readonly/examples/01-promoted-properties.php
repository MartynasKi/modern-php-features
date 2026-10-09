<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Constructor promotion');

// Before PHP 8.0: each property is written three times.
class OldPoint
{
    private int $x;
    private int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}

// Since PHP 8.0: a visibility keyword turns the parameter into a property.
class Point
{
    public function __construct(
        private int $x,
        private int $y = 0,
    ) {}
}

section('Both classes end up the same');
dump(new OldPoint(3, 4));
dump(new Point(3, 4));

section('Defaults work like normal parameters');
dump(new Point(x: 7));
