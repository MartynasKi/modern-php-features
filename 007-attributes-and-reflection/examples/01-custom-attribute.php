<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Custom attributes');

#[Attribute(Attribute::TARGET_CLASS)]
final class Table
{
    public function __construct(
        public string $name,
    ) {}
}

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Column
{
    public function __construct(
        public ?string $name = null,
    ) {}
}

#[Table('users')]
final class User
{
    #[Column('user_email')]
    public string $email;

    #[Column]
    public string $name;

    public int $loginCount = 0;
}

$class = new ReflectionClass(User::class);

section('Raw attribute data');
$attribute = $class->getAttributes(Table::class)[0];
text('Name: ' . $attribute->getName());
dump($attribute->getArguments());

section('newInstance() creates the attribute object');
dump($attribute->newInstance());

section('Map properties to columns');
foreach ($class->getProperties() as $property) {
    $attributes = $property->getAttributes(Column::class);

    if ($attributes === []) {
        text("{$property->getName()} is not a column");

        continue;
    }

    $column = $attributes[0]->newInstance();
    text("{$property->getName()} => " . ($column->name ?? $property->getName()));
}
