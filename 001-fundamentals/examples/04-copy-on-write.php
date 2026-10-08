<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Copy-on-write');

$start = memory_get_usage();
$used = fn(): string => round((memory_get_usage() - $start) / 1_048_576, 1) . ' MB';

$original = range(1, 100_000);
$afterCreate = $used();

$copy = $original;
$afterAssign = $used();

$copy[] = 100_001;
$afterChange = $used();

// Measure first and dump once, so VarDumper's own memory does not skew the numbers.
dump([
    'after creating $original' => $afterCreate,
    'after $copy = $original' => $afterAssign,
    'after changing $copy' => $afterChange,
]);
