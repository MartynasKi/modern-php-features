<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/vendor/autoload.php';

function io(): SymfonyStyle
{
    static $io;

    return $io ??= new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
}

function title(string $message): void
{
    io()->title($message);
}

function section(string $message): void
{
    io()->section($message);
}

function text(string|array $message): void
{
    io()->text($message);
}

function success(string|array $message): void
{
    io()->success($message);
}
