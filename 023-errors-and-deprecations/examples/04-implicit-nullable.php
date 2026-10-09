<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Deprecated implicit nullable types');

// Print deprecations instead of PHP's default output.
set_error_handler(function (int $level, string $message): bool {
    text("E_DEPRECATED: {$message}");

    return true;
}, E_DEPRECATED);

section('The problem: a default of null makes the type nullable');
// This deprecation is raised when PHP compiles the function. eval() compiles it
// here, after the error handler is set, so the handler can print it.
eval('function greetOld(string $name = null): string { return "Hello " . ($name ?? "guest"); }');
dump(greetOld());

section('The fix: say ?string');
function greet(?string $name = null): string
{
    return 'Hello ' . ($name ?? 'guest');
}

dump(greet());
dump(greet('Ann'));
