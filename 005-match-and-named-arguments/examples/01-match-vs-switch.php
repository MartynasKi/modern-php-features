<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('match vs switch');

function describeWithSwitch(mixed $value): string
{
    // switch compares with ==, so '1' matches 1.
    switch ($value) {
        case 1:
            return 'one';
        case 0:
            return 'zero';
        default:
            return 'something else';
    }
}

function describeWithMatch(mixed $value): string
{
    // match compares with ===, so '1' does not match 1.
    return match ($value) {
        1 => 'one',
        0 => 'zero',
        default => 'something else',
    };
}

section('switch uses loose comparison');
dump(describeWithSwitch('1'));
dump(describeWithSwitch(false));

section('match uses strict comparison');
dump(describeWithMatch('1'));
dump(describeWithMatch(false));

function shippingWithSwitch(string $country): int
{
    $price = 0;

    switch ($country) {
        // The LT case forgot its break, so the DE case runs too.
        case 'LT':
            $price = 5;
            // no break
        case 'DE':
            $price = 10;
            break;
    }

    return $price;
}

section('switch falls through without break');
dump(shippingWithSwitch('LT'));

section('match has no fall through and can share arms');
$shipping = fn(string $country): int => match ($country) {
    'LT', 'LV', 'EE' => 5,
    'DE' => 10,
};
dump($shipping('LV'));

section('match (true) replaces if and elseif chains');
$grade = fn(int $score): string => match (true) {
    $score >= 90 => 'A',
    $score >= 70 => 'B',
    default => 'C',
};
dump($grade(75));
