<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('clamp()');

section('Keep a value between a minimum and a maximum');
dump(clamp(150, 0, 100));
dump(clamp(-20, 0, 100));
dump(clamp(42, 0, 100));

section('Floats and strings work too');
dump(clamp(1.7, 0.0, 1.0));
dump(clamp('z', 'a', 'm'));

section('Before PHP 8.6');
dump(max(0, min(150, 100)));

section('min greater than max throws');
try {
    clamp(5, 10, 0);
} catch (ValueError $error) {
    dump($error->getMessage());
}
