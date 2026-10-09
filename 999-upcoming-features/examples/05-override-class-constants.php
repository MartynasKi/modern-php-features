<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('#[Override] on class constants');

interface HasTable
{
    public const TABLE = '';
}

final class User implements HasTable
{
    // PHP 8.6 checks that HasTable really has TABLE.
    #[Override]
    public const TABLE = 'users';
}

// The mistake: a typo creates a new constant instead of overriding TABLE.
// Uncomment to see the error.
//
// final class Post implements HasTable
// {
//     #[Override]
//     public const TABEL = 'posts';
// }

section('The override works');
dump(User::TABLE);
