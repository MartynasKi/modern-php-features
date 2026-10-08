<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/vendor/autoload.php';

$io = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
