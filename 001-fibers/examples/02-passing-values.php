<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Passing values in both directions');

$fiber = new Fiber(function (string $name): void {
    section('Fiber: argument received from start()');
    dump($name);

    // The question goes out through start(); the answer comes back through resume().
    $answer = Fiber::suspend("{$name}, what are you learning?");

    section('Fiber: answer received from resume()');
    dump($answer);
});

$question = $fiber->start('Martynas');

section('Main: question received from suspend()');
dump($question);

$fiber->resume('PHP fibers');

success('The fiber has finished.');
