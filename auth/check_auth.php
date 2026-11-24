<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function escapeHtml(?string $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function requireLogin(string $loginUrl = '/bookshop-inventory/login.php'): void
{
    if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
        $_SESSION['error_msg'] = 'Please log in to continue.';
        header('Location: ' . $loginUrl);
        exit();
    }
}


function requireRole(array $allowedRoles, string $redirectUrl = '/bookshop-inventory/index.php'): void
{
    requireLogin();

    $currentRole = $_SESSION['role'] ?? '';
    if (!in_array($currentRole, $allowedRoles, true)) {
        $_SESSION['error_msg'] = 'Access denied: you do not have permission to view that page.';
        header('Location: ' . $redirectUrl);
        exit();
    }
}


function requireAdmin(string $redirectUrl = '/bookshop-inventory/index.php'): void
{
    requireRole(['admin'], $redirectUrl);
}


requireLogin();