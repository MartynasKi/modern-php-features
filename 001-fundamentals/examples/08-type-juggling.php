<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Type juggling');

section('Loose comparisons with ==');
dump([
    "0 == 'a'" => 0 == 'a',
    "'1' == '01'" => '1' == '01',
    "'10' == '1e1'" => '10' == '1e1',
    "100 == '1e2'" => 100 == '1e2',
    'null == false' => null == false,
    '[] == false' => [] == false,
]);

section('Strict comparisons with ===');
dump([
    "'1' === '01'" => '1' === '01',
    'null === false' => null === false,
]);
