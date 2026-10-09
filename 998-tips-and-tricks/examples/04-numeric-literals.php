<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Numeric literals');

section('Underscores make big numbers readable (PHP 7.4)');
dump(1_000_000);
dump(0.000_001);
dump(0xFF_FF_FF);

section('0o for octal (PHP 8.1), same as a leading 0');
dump(0o755);
dump(0755);

section('Binary literals');
dump(0b1010);

section('Underscores do not work inside strings');
dump((int) '1_000');
dump(is_numeric('1_000'));
