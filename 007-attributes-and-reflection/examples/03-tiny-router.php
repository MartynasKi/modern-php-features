<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('A tiny attribute router');

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Route
{
    public function __construct(
        public string $method,
        public string $path,
    ) {}
}

final class UserController
{
    #[Route('GET', '/users')]
    public function index(): string
    {
        return 'All users';
    }

    #[Route('GET', '/users/{id}')]
    #[Route('GET', '/members/{id}')]
    public function show(string $id): string
    {
        return "User {$id}";
    }

    #[Route('POST', '/users')]
    public function store(): string
    {
        return 'User created';
    }
}

function routesFor(string $controller): array
{
    $routes = [];

    foreach ((new ReflectionClass($controller))->getMethods() as $method) {
        foreach ($method->getAttributes(Route::class) as $attribute) {
            $route = $attribute->newInstance();
            $routes[] = [$route->method, $route->path, [$controller, $method->getName()]];
        }
    }

    return $routes;
}

function dispatch(array $routes, string $method, string $uri): string
{
    foreach ($routes as [$routeMethod, $path, [$controller, $action]]) {
        // Turn /users/{id} into a regex with a named group for id.
        $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?<$1>[^/]+)', $path) . '$#';

        if ($routeMethod === $method && preg_match($pattern, $uri, $matches)) {
            $parameters = array_filter($matches, is_string(...), ARRAY_FILTER_USE_KEY);

            // Named groups become named arguments.
            return new $controller()->{$action}(...$parameters);
        }
    }

    return '404 Not Found';
}

$routes = routesFor(UserController::class);

section('Routes found with reflection');
foreach ($routes as [$method, $path, [, $action]]) {
    text("{$method} {$path} => {$action}()");
}

section('Dispatch some requests');
foreach ([['GET', '/users'], ['GET', '/members/7'], ['POST', '/users'], ['DELETE', '/users']] as [$method, $uri]) {
    text("{$method} {$uri} => " . dispatch($routes, $method, $uri));
}
