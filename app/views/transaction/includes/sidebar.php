<?php
$sessionRole = (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user']['role']))
    ? $_SESSION['user']['role']
    : null;
$sidebarRole = resolve_sidebar_role($sidebarRole ?? null, $_GET['role'] ?? $sessionRole);
$activeMenu = $activeMenu ?? '';
include __DIR__ . '/../../layouts/sidebar.php';
