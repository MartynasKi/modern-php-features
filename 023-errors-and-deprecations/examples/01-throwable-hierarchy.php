<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('The Throwable hierarchy');

$classes = [
    Exception::class,
    RuntimeException::class,
    JsonException::class,
    Error::class,
    TypeError::class,
    ArgumentCountError::class,
    ValueError::class,
    ArithmeticError::class,
    DivisionByZeroError::class,
    UnhandledMatchError::class,
];

section('Each class and its parents');
foreach ($classes as $class) {
    text(implode(' -> ', [$class, ...array_values(class_parents($class))]) . ' -> Throwable');
}

section('catch (Exception) misses an Error');
try {
    try {
        intdiv(1, 0);
    } catch (Exception $exception) {
        text('Caught as Exception');
    }
} catch (Error $error) {
    text('Not caught as Exception, but caught as ' . $error::class);
}

section('catch (Throwable) catches both');
foreach ([fn() => intdiv(1, 0), fn() => throw new RuntimeException('Oops')] as $code) {
    try {
        $code();
    } catch (Throwable $throwable) {
        text('Caught ' . $throwable::class);
    }
}
