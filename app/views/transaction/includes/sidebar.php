<?php
$sidebarRole = $sidebarRole ?? ($_GET['role'] ?? ($_SESSION['role'] ?? 'kasir'));
$activeMenu = $activeMenu ?? '';
include __DIR__ . '/../../layouts/sidebar.php';
