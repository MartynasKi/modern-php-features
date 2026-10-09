<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('#[Override]');

class Notification
{
    public function toMail(): string
    {
        return 'Default mail';
    }
}

final class WelcomeNotification extends Notification
{
    // PHP checks that the parent really has toMail().
    #[Override]
    public function toMail(): string
    {
        return 'Welcome aboard!';
    }
}

// The mistake: a typo in the method name. Without #[Override], PHP would quietly
// add a new method and keep using the parent toMail(). Uncomment to see the error.
//
// final class InvoiceNotification extends Notification
// {
//     #[Override]
//     public function toMial(): string
//     {
//         return 'Your invoice';
//     }
// }

section('The override works');
dump(new WelcomeNotification()->toMail());

section('Without #[Override], a typo goes unnoticed');
$typo = new class extends Notification {
    public function toMial(): string
    {
        return 'Your invoice';
    }
};
dump($typo->toMail());
