<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

$io->title('Passing values in both directions');

$fiber = new Fiber(function (string $name) use ($io): void {
    $io->section('Fiber: argument received from start()');
    dump($name);

    // The question goes out through start(); the answer comes back through resume().
    $answer = Fiber::suspend("{$name}, what are you learning?");

    $io->section('Fiber: answer received from resume()');
    dump($answer);
});

$question = $fiber->start('Martynas');

$io->section('Main: question received from suspend()');
dump($question);

$fiber->resume('PHP fibers');

$io->success('The fiber has finished.');
