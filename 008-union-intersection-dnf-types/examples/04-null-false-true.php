<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('null, false and true as types');

// Many old functions return a value or false, like strpos().
function firstWord(string $text): string|false
{
    return strtok($text, ' ');
}

// Since PHP 8.2, true can be a type on its own.
function alwaysSucceeds(): true
{
    return true;
}

// Since PHP 8.2, null can be a type on its own.
function nothing(): null
{
    return null;
}

function onlyFalse(false $value): false
{
    return $value;
}

section('string|false');
dump(firstWord('Hello world'));
dump(firstWord(''));

section('true');
dump(alwaysSucceeds());

section('null');
dump(nothing());

section('false is strict');
try {
    onlyFalse(true);
} catch (TypeError $error) {
    dump(strstr($error->getMessage(), ', called in', true));
}
