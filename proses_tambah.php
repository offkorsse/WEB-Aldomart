<?php
session_start();
include 'auth_check.php'; // proteksi: harus login
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah.php");
    exit;
}

$name     = trim($_POST['nama']);
$category = trim($_POST['kategori']);
$price    = (int)$_POST['harga'];
$stock    = (int)$_POST['stok'];

// ── Validasi input dasar ──────────────────────────────
if ($name === '' || $category === '' || $price < 0 || $stock < 0) {
    header("Location: tambah.php?msg=invalid");
    exit;
}

// ── Validasi file wajib diupload ──────────────────────
if (empty($_FILES['gambar']['name'])) {
    header("Location: tambah.php?msg=no_file");
    exit;
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$file_type     = mime_content_type($_FILES['gambar']['tmp_name']);

if (!in_array($file_type, $allowed_types)) {
    header("Location: tambah.php?msg=invalid_file");
    exit;
}

// ── Proses upload ─────────────────────────────────────
$dir_target = "gambar/";
if (!is_dir($dir_target)) {
    mkdir($dir_target, 0755, true);
}

$ext       = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
$safe_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$path      = $dir_target . $safe_name;

if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
    header("Location: tambah.php?msg=upload_gagal");
    exit;
}

// ── Insert ke database dengan prepared statement ──────
$stmt = $conn->prepare("INSERT INTO products (nama, harga, stok, kategori, gambar) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('siiss', $name, $price, $stock, $category, $path);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: admin.php?msg=tambah_ok");
} else {
    $stmt->close();
    // Hapus file yang sudah diupload jika insert gagal
    if (file_exists($path)) unlink($path);
    header("Location: admin.php?msg=error");
}
exit;
?>
