<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Copy-on-write');

$start = memory_get_usage();
$original = range(1, 100_000);
$afterCreate = memory_get_usage();

$copy = $original;
$afterAssign = memory_get_usage();

$copy[] = 100_001;
$afterWrite = memory_get_usage();

dump([
    'create the array' => $afterCreate - $start,
    'assign it to $copy' => $afterAssign - $afterCreate,
    'change $copy' => $afterWrite - $afterAssign,
]);
