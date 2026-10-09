<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('array_is_list');

// A list has keys 0, 1, 2 and so on, in order.
$examples = [
    'list' => ['a', 'b', 'c'],
    'empty' => [],
    'string keys' => ['a' => 1],
    'gap after unset' => (function () {
        $items = ['a', 'b', 'c'];
        unset($items[1]);

        return $items;
    })(),
    'wrong order' => [1 => 'b', 0 => 'a'],
];

foreach ($examples as $name => $array) {
    text($name . ': ' . (array_is_list($array) ? 'list' : 'not a list'));
}

section('Why it matters: json_encode() picks [] or {}');
dump(json_encode(['a', 'b']));
dump(json_encode([1 => 'b', 2 => 'c']));
dump(json_encode(array_values([1 => 'b', 2 => 'c'])));
