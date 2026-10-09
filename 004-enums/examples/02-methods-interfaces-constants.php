<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Methods, interfaces and constants');

interface HasLabel
{
    public function getLabel(): string;
}

enum Status: string implements HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public const DEFAULT = self::Draft;

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function isVisible(): bool
    {
        return $this === self::Published;
    }

    public static function getVisible(): array
    {
        return array_filter(self::cases(), fn(self $status) => $status->isVisible());
    }
}

section('Instance methods');
dump(Status::Archived->getLabel());
dump(Status::Archived->isVisible());

section('Static method');
dump(Status::getVisible());

section('Constant that points to a case');
dump(Status::DEFAULT);

section('Interface');
dump(Status::Draft instanceof HasLabel);
