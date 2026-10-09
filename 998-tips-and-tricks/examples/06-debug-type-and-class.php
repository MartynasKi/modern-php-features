<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('get_debug_type() and $object::class');

$values = [42, 4.2, 'text', true, null, [1, 2], new ArrayObject(), fn() => 1];

section('gettype() vs get_debug_type() (PHP 8.0)');
foreach ($values as $value) {
    text(str_pad(gettype($value), 10) . ' ' . get_debug_type($value));
}

section('$object::class instead of get_class($object) (PHP 8.0)');
$object = new ArrayObject();
dump($object::class);
