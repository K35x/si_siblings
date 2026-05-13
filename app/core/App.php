<?php

class App
{
    public static function run(array $routes): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $basePath = app_base_url();

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        if ($path === '/index.php') {
            $path = '/';
        } elseif (str_starts_with($path, '/index.php/')) {
            $path = substr($path, strlen('/index.php'));
        }

        if (!array_key_exists($path, $routes)) {
            http_response_code(404);
            echo 'Halaman tidak ditemukan';
            return;
        }

        [$controllerClass, $method] = $routes[$path];
        $controller = new $controllerClass();
        $controller->{$method}();
    }
}
