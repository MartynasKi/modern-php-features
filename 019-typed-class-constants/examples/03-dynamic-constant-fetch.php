<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Dynamic constant fetch');

final class Config
{
    public const string CACHE_DRIVER = 'redis';

    public const string QUEUE_DRIVER = 'database';
}

enum Status: string
{
    case Draft = 'draft';
    case Published = 'published';
}

$name = 'CACHE_DRIVER';
$class = Config::class;

section('Before PHP 8.3: constant() with a string');
dump(constant(Config::class . '::' . $name));

section('PHP 8.3: ::{$name}');
dump(Config::{$name});
dump($class::{'QUEUE_DRIVER'});

section('Enum cases work the same way');
$case = 'Published';
dump(Status::{$case});

section('A missing constant throws an Error');
try {
    Config::{'MAIL_DRIVER'};
} catch (Error $error) {
    dump($error->getMessage());
}
