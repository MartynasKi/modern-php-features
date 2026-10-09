<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Final class constants');

class Model
{
    public const string DATE_FORMAT = 'Y-m-d';

    // Since PHP 8.1, final stops children from overriding a constant.
    final public const string PRIMARY_KEY = 'id';
}

class Post extends Model
{
    public const string DATE_FORMAT = 'd.m.Y';

    // Overriding a final constant is a fatal error. Uncomment to see it.
    // public const string PRIMARY_KEY = 'uuid';
}

section('A normal constant can be overridden');
dump(Model::DATE_FORMAT);
dump(Post::DATE_FORMAT);

section('A final constant stays the same everywhere');
dump(Post::PRIMARY_KEY);
