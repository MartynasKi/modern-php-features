<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Asymmetric visibility vs readonly');

class Order
{
    public function __construct(
        // Set once, never changed again.
        public readonly int $id,
        // Changed by this class and its children only.
        public protected(set) string $status = 'new',
    ) {}

    public function ship(): void
    {
        $this->status = 'shipped';
    }

    public function changeId(int $id): void
    {
        $this->id = $id;
    }
}

final class PriorityOrder extends Order
{
    public function rush(): void
    {
        $this->status = 'rushed';
    }
}

$order = new PriorityOrder(42);

section('A protected(set) property can change many times inside');
$order->ship();
dump($order->status);
$order->rush();
dump($order->status);

section('A readonly property cannot change, even inside');
try {
    $order->changeId(43);
} catch (Error $error) {
    dump($error->getMessage());
}

section('Both block writes from outside');
try {
    $order->status = 'cancelled';
} catch (Error $error) {
    dump($error->getMessage());
}

try {
    $order->id = 43;
} catch (Error $error) {
    dump($error->getMessage());
}
