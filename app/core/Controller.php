<?php

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

        if (! is_file($viewPath)) {
            http_response_code(500);
            echo 'View tidak ditemukan: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }
}
