<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Garbage collector');

final class Node
{
    public ?Node $other = null;

    public function __construct(public string $name) {}

    public function __destruct()
    {
        text("{$this->name} destroyed");
    }
}

$a = new Node('A');
$b = new Node('B');

$a->other = $b;
$b->other = $a;

section('unset($a, $b)');
unset($a, $b);
text('Nothing destroyed. Each node still holds the other.');

section('gc_collect_cycles()');
dump(gc_collect_cycles());
