<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Compared to older callables');

final class Report
{
    public function formatter(): Closure
    {
        // The closure is created inside the class, so it may call a private method.
        return $this->format(...);
    }

    private function format(string $row): string
    {
        return strtoupper($row);
    }
}

section('Three ways to make the same callable');
dump(array_map('strtoupper', ['a']));
dump(array_map(Closure::fromCallable('strtoupper'), ['a']));
dump(array_map(strtoupper(...), ['a']));

section('A typo in a string fails only when it is called');
$callable = 'strtoupperr';

try {
    $callable('a');
} catch (Error $error) {
    dump($error->getMessage());
}

section('A typo with (...) fails where you wrote it');
try {
    $closure = strtoupperr(...);
} catch (Error $error) {
    dump($error->getMessage());
}

$report = new Report();

section('A closure from inside the class can be used outside');
dump(array_map($report->formatter(), ['a', 'b']));

section('An array callable from outside cannot reach the private method');
dump(is_callable([$report, 'format']));
