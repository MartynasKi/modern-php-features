<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('A cache that does not leak');

final class Invoice
{
    public function __construct(
        public array $lines,
    ) {}
}

final class ArrayCache
{
    public array $totals = [];

    public function total(Invoice $invoice): int
    {
        // The array keeps every invoice alive as long as the cache lives.
        $id = spl_object_id($invoice);
        $this->totals[$id] ??= [$invoice, array_sum($invoice->lines)];

        return $this->totals[$id][1];
    }
}

final class WeakCache
{
    public WeakMap $totals;

    public function __construct()
    {
        $this->totals = new WeakMap();
    }

    public function total(Invoice $invoice): int
    {
        // The entry disappears when the invoice is destroyed.
        return $this->totals[$invoice] ??= array_sum($invoice->lines);
    }
}

function handleInvoices(ArrayCache|WeakCache $cache): string
{
    $before = memory_get_usage();

    for ($i = 0; $i < 1000; $i++) {
        $invoice = new Invoice(range(1, 100));
        $cache->total($invoice);
        $invoice = null;
    }

    $kept = number_format((memory_get_usage() - $before) / 1024) . ' KB';

    return count($cache->totals) . " entries, {$kept} still in use";
}

section('1,000 short-lived invoices with an array cache');
text(handleInvoices(new ArrayCache()));

section('1,000 short-lived invoices with a WeakMap cache');
text(handleInvoices(new WeakCache()));
