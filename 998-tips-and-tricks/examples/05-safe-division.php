<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Division tricks');

section('intdiv() returns an int, / may return a float');
dump(7 / 2);
dump(intdiv(7, 2));
dump(6 / 2);

section('Dividing by zero throws');
try {
    1 / 0;
} catch (DivisionByZeroError $error) {
    dump($error->getMessage());
}

section('fdiv() follows IEEE 754 and returns INF or NAN instead');
dump(fdiv(1, 0));
dump(fdiv(-1, 0));
dump(fdiv(0, 0));
dump(is_nan(fdiv(0, 0)));

section('% works on ints, fmod() on floats');
dump(7 % 3);
dump(fmod(7.5, 2));
