<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('First-class callable syntax');

final class Formatter
{
    public function __construct(
        private string $currency,
    ) {}

    public function price(int $cents): string
    {
        return number_format($cents / 100, 2) . " {$this->currency}";
    }

    public static function slug(string $text): string
    {
        return strtolower(str_replace(' ', '-', $text));
    }
}

$formatter = new Formatter('EUR');

section('A built-in function');
$length = strlen(...);
dump($length('modern'));

section('An object method keeps its object');
$price = $formatter->price(...);
dump($price(1999));

section('A static method');
$slug = Formatter::slug(...);
dump($slug('Modern PHP Features'));

section('Pass them straight to array_map()');
dump(array_map($price, [500, 1250]));

section('The result is always a Closure');
dump(get_debug_type($length));
