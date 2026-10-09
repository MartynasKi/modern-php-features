<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('public private(set)');

final class BankAccount
{
    // Anyone can read the balance. Only this class can change it.
    public private(set) int $balance = 0;

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            throw new DomainException('Not enough money');
        }

        $this->balance -= $amount;
    }
}

$account = new BankAccount();

section('Changed through methods');
$account->deposit(100);
$account->withdraw(30);
dump($account->balance);

section('Changed directly from outside');
try {
    $account->balance = 1_000_000;
} catch (Error $error) {
    dump($error->getMessage());
}
