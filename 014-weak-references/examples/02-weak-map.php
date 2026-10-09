<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('WeakMap');

final class Request {}

$visits = new WeakMap();

$first = new Request();
$second = new Request();

// Objects are the keys. The map does not keep them alive.
$visits[$first] = 3;
$visits[$second] = 1;

section('Two entries');
dump(count($visits));
dump($visits[$first]);

section('Destroy one key object');
unset($first);
dump(count($visits));

section('Only objects can be keys');
try {
    $visits['home'] = 1;
} catch (TypeError $error) {
    dump($error->getMessage());
}
