<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('match with enums');

enum Status
{
    case Draft;
    case Published;
    case Archived;
}

function badgeColor(Status $status): string
{
    return match ($status) {
        Status::Draft => 'gray',
        Status::Published => 'green',
        Status::Archived => 'red',
    };
}

function forgotArchived(Status $status): string
{
    return match ($status) {
        Status::Draft => 'gray',
        Status::Published => 'green',
    };
}

section('Every case is handled');
foreach (Status::cases() as $status) {
    text("{$status->name} => " . badgeColor($status));
}

section('A missing case fails at runtime');
try {
    forgotArchived(Status::Archived);
} catch (UnhandledMatchError $error) {
    dump($error->getMessage());
}
