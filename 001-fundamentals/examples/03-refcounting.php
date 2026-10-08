<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Refcounting');

// debug_zval_dump() holds the value too, so each count is one higher.

$first = new stdClass();

section('One variable');
debug_zval_dump($first);

$second = $first;

section('Two variables');
debug_zval_dump($first);

unset($second);

section('After unset($second)');
debug_zval_dump($first);
