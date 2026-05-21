<?php
/**
 * auth_check.php
 * Include file ini di setiap halaman yang butuh proteksi login.
 * Pastikan session_start() sudah dipanggil sebelum include ini,
 * atau panggil di sini jika belum.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
