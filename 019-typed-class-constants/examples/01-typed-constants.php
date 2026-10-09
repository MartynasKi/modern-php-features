<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Typed class constants');

interface HasTable
{
    // Every class that implements this must keep TABLE a string.
    public const string TABLE = '';
}

class User implements HasTable
{
    public const string TABLE = 'users';

    public const int PER_PAGE = 15;

    public const array SORTABLE = ['name', 'created_at'];

    public const ?string CONNECTION = null;
}

// Overriding with another type is a fatal error. Uncomment to see it.
//
// class Admin extends User
// {
//     public const int TABLE = 1;
// }

section('Typed constants read like any other');
dump(User::TABLE);
dump(User::PER_PAGE);
dump(User::SORTABLE);
dump(User::CONNECTION);

section('Reflection shows the type');
dump((string) new ReflectionClassConstant(User::class, 'PER_PAGE')->getType());
