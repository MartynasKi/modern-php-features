<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Deprecated dynamic properties');

// Print deprecations instead of PHP's default output.
set_error_handler(function (int $level, string $message): bool {
    text("E_DEPRECATED: {$message}");

    return true;
}, E_DEPRECATED);

final class User
{
    public string $name = 'Ann';
}

section('The problem: a typo creates a new property');
$user = new User();
$user->nmae = 'Bob';
dump($user->name);

section('Fix 1: declare the property');
final class Post
{
    public string $title = '';

    public ?string $slug = null;
}

$post = new Post();
$post->slug = 'hello';
text('No deprecation');

section('Fix 2: opt in with #[AllowDynamicProperties]');
#[AllowDynamicProperties]
final class LegacyModel {}

$model = new LegacyModel();
$model->anything = 'still works';
text('No deprecation');

section('stdClass always allows them');
$data = new stdClass();
$data->anything = 'still works';
text('No deprecation');
