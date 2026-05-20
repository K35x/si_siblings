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

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_currency')) {
    function format_currency(mixed $value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }
}

if (!function_exists('format_date_id')) {
    function format_date_id(mixed $value, bool $includeTime = false): string
    {
        if (empty($value)) {
            return '-';
        }

        try {
            $date = new DateTime((string) $value);
        } catch (Exception) {
            return '-';
        }

        if (!$includeTime) {
            return $date->format('d/m/Y');
        }

        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y H:i');
    }
}

if (!function_exists('resolve_sidebar_role')) {
    function resolve_sidebar_role(?string $preferred = null, ?string $secondary = null, string $fallback = 'kasir'): string
    {
        $role = $preferred ?? ($secondary ?? $fallback);

        return in_array($role, ['kasir', 'owner'], true) ? $role : $fallback;
    }
}
