<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('A tiny lazy container');

final class Container
{
    private array $factories = [];

    private array $services = [];

    public function set(string $class, Closure $factory): void
    {
        $this->factories[$class] = $factory;
    }

    public function get(string $class): object
    {
        // Hand out a lazy proxy. The factory runs on first use.
        return $this->services[$class] ??= new ReflectionClass($class)
            ->newLazyProxy(fn() => ($this->factories[$class])());
    }
}

final class Database
{
    private string $connection;

    public function __construct()
    {
        text('Opening a database connection');
        $this->connection = 'mysql';
    }

    public function query(string $sql): string
    {
        return "{$this->connection}: {$sql}";
    }
}

final class Cache
{
    private string $connection;

    public function __construct()
    {
        text('Connecting to the cache server');
        $this->connection = 'redis';
    }
}

$container = new Container();
$container->set(Database::class, fn() => new Database());
$container->set(Cache::class, fn() => new Cache());

section('Get both services');
$database = $container->get(Database::class);
$cache = $container->get(Cache::class);
text('Nothing was opened yet');

section('Use only the database');
dump($database->query('SELECT 1'));

section('The cache was never used, so it never connected');
dump(new ReflectionClass(Cache::class)->isUninitializedLazyObject($cache));
