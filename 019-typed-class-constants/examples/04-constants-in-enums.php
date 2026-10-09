<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Constants in enums');

enum Status: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Published = 'published';

    // A constant can point to a case or hold a list of cases.
    public const self DEFAULT = self::Draft;

    public const array EDITABLE = [self::Draft, self::Review];

    public function isEditable(): bool
    {
        return in_array($this, self::EDITABLE, true);
    }
}

section('A typed constant that points to a case');
dump(Status::DEFAULT);

section('A list of cases');
foreach (Status::cases() as $status) {
    text("{$status->name}: " . ($status->isEditable() ? 'editable' : 'locked'));
}
