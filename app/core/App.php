<?php

class App
{
    public static function run(array $routes, array $publicRoutes = [], array $roleMap = []): void
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

        // Try exact match first
        if (array_key_exists($path, $routes)) {
            [$controllerClass, $method] = $routes[$path];
        } else {
            // Try dynamic route match
            $matched = false;
            foreach ($routes as $pattern => $handler) {
                if (strpos($pattern, '{category}') !== false) {
                    $basePattern = str_replace('{category}', '', $pattern);
                    if (strpos($path, $basePattern) === 0) {
                        $category = substr($path, strlen($basePattern));
                        if (!empty($category)) {
                            [$controllerClass, $method] = $handler;
                            $_GET['category'] = $category;
                            $matched = true;
                            break;
                        }
                    }
                }
            }
            if (!$matched) {
                self::renderError(404);
                return;
            }
        }

        // Auth guard
        if (!in_array($path, $publicRoutes, true)) {
            if (!isset($_SESSION['user'])) {
                header('Location: ' . url('/login'));
                exit;
            }

            // RBAC check
            if (isset($roleMap[$path])) {
                $allowedRoles = $roleMap[$path];
                if (!in_array($_SESSION['user']['role'], $allowedRoles, true)) {
                    self::renderError(403);
                    exit;
                }
            }
        }

        if ($method === 'dynamicForm') {
            $controller = new $controllerClass();
            $controller->$method($_GET['category'] ?? '');
        } else {
            $controller = new $controllerClass();
            $controller->$method();
        }
    }

    private static function renderError(int $statusCode): void
    {
        http_response_code($statusCode);

        $data = [
            'statusCode' => $statusCode,
            'title' => $statusCode === 403 ? 'Akses ditolak' : 'Halaman tidak ditemukan',
            'message' => $statusCode === 403
                ? 'Akun kamu tidak punya izin untuk membuka halaman ini.'
                : 'Alamat yang kamu tuju tidak tersedia atau sudah dipindahkan.',
        ];

        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/errors/error.php';
        exit;
    }
}
