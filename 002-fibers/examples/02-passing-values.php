<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Pass values in and out');

$fiber = new Fiber(function (string $name): void {
    $answer = Fiber::suspend("Hi {$name}, how are you?");

    section('Fiber got from resume()');
    dump($answer);
});

$question = $fiber->start('Martynas');

section('Main got from suspend()');
dump($question);

$fiber->resume('Great, thanks!');
