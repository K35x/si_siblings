<?php
$pageTitle = $pageTitle ?? 'Siblings.co';
$pageStyles = $pageStyles ?? [];
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#4a3328" media="(prefers-color-scheme: light)">
<meta name="color-scheme" content="light">
<meta name="format-detection" content="telephone=no">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<title><?= e($pageTitle) ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/theme.css') ?>">
<link rel="stylesheet" href="<?= asset('css/components.css') ?>">
<?php foreach ($pageStyles as $style): ?>
<link rel="stylesheet" href="<?= asset('css/' . ltrim($style, '/')) ?>">
<?php endforeach; ?>
