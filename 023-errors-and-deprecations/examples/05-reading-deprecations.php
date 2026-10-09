<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Reading deprecation notices');

$notices = [];

// Collect every deprecation with its location, like a test runner or logger does.
set_error_handler(function (int $level, string $message, string $file, int $line) use (&$notices): bool {
    $notices[] = [
        'level' => $level === E_DEPRECATED ? 'E_DEPRECATED' : 'E_USER_DEPRECATED',
        'message' => $message,
        'where' => basename($file) . ':' . $line,
    ];

    return true;
}, E_DEPRECATED | E_USER_DEPRECATED);

final class Cart {}

// Three typical upgrade notices.
$cart = new Cart();
$cart->total = 10;

$prices = [];
$prices[null] = 5;

trigger_error('Cart::sum() is deprecated, use Cart::total() instead', E_USER_DEPRECATED);

section('What the code raised');
dump($notices);

section('Make sure you see them');
dump(error_reporting() & E_DEPRECATED ? 'E_DEPRECATED is reported' : 'E_DEPRECATED is hidden');
