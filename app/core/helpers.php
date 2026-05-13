<?php

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base = rtrim(str_replace('/index.php', '', $scriptName), '/');

        return $base === '/' ? '' : $base;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $path = '/' . ltrim($path, '/');
        $base = app_base_url();

        if ($path === '/') {
            return $base === '' ? '/' : $base;
        }

        return $base . $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url('/assets/' . ltrim($path, '/'));
    }
}
