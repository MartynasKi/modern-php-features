<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('get and set hooks');

final class User
{
    public string $email {
        // Runs on every write. $value is the new value.
        set(string $value) {
            if (! str_contains($value, '@')) {
                throw new ValueError("Invalid email: {$value}");
            }

            $this->email = strtolower(trim($value));
        }
    }

    public string $name {
        // Runs on every read.
        get => ucfirst($this->name);
    }

    public function __construct(string $email, string $name)
    {
        // Hooks run inside the class too, including the constructor.
        $this->email = $email;
        $this->name = $name;
    }
}

$user = new User('  Ann@Example.COM ', 'ann');

section('The set hook cleaned the email');
dump($user->email);

section('The get hook formats the name');
dump($user->name);

section('The set hook validates');
try {
    $user->email = 'not-an-email';
} catch (ValueError $error) {
    dump($error->getMessage());
}
