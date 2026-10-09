<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('#[Deprecated]');

// Print deprecation messages instead of PHP's default output.
set_error_handler(function (int $level, string $message): bool {
    text("E_USER_DEPRECATED: {$message}");

    return true;
}, E_USER_DEPRECATED);

#[Deprecated('use formatMoney() instead', since: '2.0')]
function money(int $cents): string
{
    return formatMoney($cents);
}

function formatMoney(int $cents): string
{
    return number_format($cents / 100, 2);
}

final class Order
{
    #[Deprecated('use Order::STATUS_PAID')]
    public const PAID = 'paid';

    public const STATUS_PAID = 'paid';

    #[Deprecated(since: '2.0')]
    public function total(): int
    {
        return 1000;
    }
}

section('A deprecated function');
dump(money(1999));

section('A deprecated class constant');
dump(Order::PAID);

section('A deprecated method');
dump(new Order()->total());

section('The replacements stay quiet');
dump(formatMoney(1999));
dump(Order::STATUS_PAID);
