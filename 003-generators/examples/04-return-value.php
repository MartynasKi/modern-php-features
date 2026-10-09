<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Generator return values');

function importRows(array $rows): Generator
{
    $imported = 0;

    foreach ($rows as $row) {
        yield $row;
        $imported++;
    }

    return $imported;
}

section('Yielded rows');
$import = importRows(['Ann', 'Bob', 'Cid']);

foreach ($import as $row) {
    text("Importing {$row}");
}

section('getReturn() after the generator finishes');
dump($import->getReturn());

function importBatches(): Generator
{
    // yield from passes the rows through and returns the inner return value.
    $first = yield from importRows(['Ann', 'Bob']);
    $second = yield from importRows(['Cid']);

    return $first + $second;
}

section('yield from collects inner return values');
$batches = importBatches();
iterator_count($batches);
dump($batches->getReturn());

section('getReturn() before the end throws');
$early = importRows(['Dan']);

try {
    $early->getReturn();
} catch (Exception $exception) {
    dump($exception->getMessage());
}
