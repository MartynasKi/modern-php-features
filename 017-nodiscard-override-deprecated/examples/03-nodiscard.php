<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('#[NoDiscard]');

// Print warnings instead of PHP's default output.
// User functions raise E_USER_WARNING and built-in ones raise E_WARNING.
set_error_handler(function (int $level, string $message): bool {
    text("Warning: {$message}");

    return true;
}, E_WARNING | E_USER_WARNING);

#[NoDiscard('the original array is not changed')]
function withoutEmpty(array $items): array
{
    return array_values(array_filter($items));
}

$tags = ['php', '', 'laravel'];

section('The mistake: ignoring the result');
withoutEmpty($tags);
dump($tags);

section('The fix: use the result');
$tags = withoutEmpty($tags);
dump($tags);

section('Ignore it on purpose with (void)');
(void) withoutEmpty($tags);
text('No warning');

section('Built-in methods use it too');
$date = new DateTimeImmutable('2026-01-01');
$date->modify('+1 day');
dump($date->format('Y-m-d'));
