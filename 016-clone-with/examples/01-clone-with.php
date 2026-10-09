<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('clone with');

final class Settings
{
    public function __construct(
        public string $theme = 'light',
        public string $language = 'en',
        public int $fontSize = 14,
    ) {}
}

$defaults = new Settings();

section('clone() takes an array of new property values');
$custom = clone($defaults, ['theme' => 'dark', 'language' => 'lt']);
dump($custom);

section('The original is unchanged');
dump($defaults);

section('Values are converted, even with strict types');
$bigger = clone($defaults, ['fontSize' => '16']);
dump($bigger->fontSize);

section('A value that cannot be converted throws');
try {
    clone($defaults, ['fontSize' => 'big']);
} catch (TypeError $error) {
    dump($error->getMessage());
}
