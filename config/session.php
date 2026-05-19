<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login()
{
    if (!isset($_SESSION['id_user'], $_SESSION['role'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function require_role($role)
{
    require_login();

    if ($_SESSION['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function current_user_id()
{
    return $_SESSION['id_user'] ?? null;
}

function current_user_name()
{
    return $_SESSION['nama'] ?? 'Pengguna';
}
