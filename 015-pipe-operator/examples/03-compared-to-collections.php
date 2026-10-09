<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Pipes vs collections');

$orders = [
    ['customer' => 'ann', 'total' => 120],
    ['customer' => 'bob', 'total' => 40],
    ['customer' => 'cid', 'total' => 300],
];

section('Customers with big orders, nested');
dump(implode(', ', array_map(
    fn(array $order) => ucfirst($order['customer']),
    array_filter($orders, fn(array $order) => $order['total'] >= 100),
)));

section('The same with pipes');
$customers = $orders
    |> (fn(array $orders) => array_filter($orders, fn(array $order) => $order['total'] >= 100))
    |> (fn(array $orders) => array_map(fn(array $order) => ucfirst($order['customer']), $orders))
    |> (fn(array $names) => implode(', ', $names));
dump($customers);
