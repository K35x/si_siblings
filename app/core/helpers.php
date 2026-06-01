<?php

if (! function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base = rtrim(str_replace('/index.php', '', $scriptName), '/');

        if ($base === '/public') {
            return '';
        }

        return $base === '/' ? '' : $base;
    }
}

if (! function_exists('url')) {
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

if (! function_exists('asset')) {
    function asset(string $path): string
    {
        $relative = ltrim($path, '/');
        $baseDir = realpath(__DIR__ . '/../../public/assets');

        if ($baseDir === false || str_contains($relative, '..')) {
            return url('/assets/' . $relative);
        }

        $absolute = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $version = is_file($absolute) ? filemtime($absolute) : null;

        $url = url('/assets/' . $relative);
        return $version ? $url . '?v=' . $version : $url;
    }
}

if (! function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('format_currency')) {
    function format_currency(mixed $value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }
}

if (! function_exists('format_date_id')) {
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

        if (! $includeTime) {
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

if (! function_exists('countOptions')) {
    function countOptions(array $optionMap, int $variantId): int
    {
        if (! isset($optionMap[$variantId])) {
            return 0;
        }

        $sum = 0;
        foreach ($optionMap[$variantId] as $sizeMap) {
            foreach ($sizeMap as $sleeveData) {
                foreach ($sleeveData as $optData) {
                    $sum += isset($optData['quantity']) ? (int) $optData['quantity'] : 1;
                }
            }
        }
        return $sum;
    }
}

if (! function_exists('resolve_sidebar_role')) {
    function resolve_sidebar_role(?string $preferred = null, ?string $secondary = null, string $fallback = Model::ROLE_KASIR): string
    {
        $role = $preferred ?? ($secondary ?? $fallback);

        return in_array($role, [Model::ROLE_KASIR, Model::ROLE_OWNER], true) ? $role : $fallback;
    }
}

if (! function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (! function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (! function_exists('csrf_verify')) {
    function csrf_verify(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}

if (! function_exists('csrf_verify_unified')) {
    function csrf_verify_unified(): bool
    {
        $postToken = $_POST['_token'] ?? null;
        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        return (is_string($postToken) && csrf_verify($postToken))
            || (is_string($headerToken) && csrf_verify($headerToken));
    }
}
