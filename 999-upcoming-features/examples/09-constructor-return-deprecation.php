<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Deprecated: returning a value from __construct()');

// Print deprecations instead of PHP's default output.
set_error_handler(function (int $level, string $message): bool {
    text("E_DEPRECATED: {$message}");

    return true;
}, E_DEPRECATED);

section('The returned value was always thrown away');
// This deprecation is raised when PHP compiles the class. eval() compiles it
// here, after the error handler is set, so the handler can print it.
eval('final class Report { public function __construct() { return "ignored"; } }');
dump(new Report());
