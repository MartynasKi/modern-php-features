<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('never and static return types');

// never: the function throws or exits and never returns.
function abort(int $code): never
{
    throw new RuntimeException("Aborted with {$code}");
}

function userName(?string $name): string
{
    // never fits anywhere, because the call never produces a value.
    return $name ?? abort(404);
}

section('never');
dump(userName('Ann'));

try {
    userName(null);
} catch (RuntimeException $exception) {
    dump($exception->getMessage());
}

class QueryBuilder
{
    protected array $parts = [];

    // static: the return type is the class the method was called on.
    public function where(string $condition): static
    {
        $this->parts[] = $condition;

        return $this;
    }
}

final class UserQuery extends QueryBuilder
{
    public function active(): static
    {
        return $this->where('active = 1');
    }
}

section('static keeps the subclass in a fluent chain');
$query = new UserQuery()->where('age > 18')->active();
dump($query);
