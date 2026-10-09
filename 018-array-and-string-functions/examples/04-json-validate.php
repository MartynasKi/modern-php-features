<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('json_validate');

$inputs = [
    '{"name": "Ann"}',
    '[1, 2, 3]',
    '"just a string"',
    '{name: "Ann"}',
    '{"name": "Ann",}',
    '',
];

foreach ($inputs as $json) {
    text(var_export($json, true) . ' => ' . (json_validate($json) ? 'valid' : 'invalid: ' . json_last_error_msg()));
}

section('Before PHP 8.3: decode the whole thing just to check it');
$json = '{"name": "Ann"}';
json_decode($json);
dump(json_last_error() === JSON_ERROR_NONE);
