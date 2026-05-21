<?php
session_start();
include 'auth_check.php'; // proteksi: harus login
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php");
    exit;
}

$id          = (int)$_POST['id'];
$name        = trim($_POST['nama']);
$category    = trim($_POST['kategori']);
$price       = (int)$_POST['harga'];
$stock       = (int)$_POST['stok'];
$gambar_lama = $_POST['gambar_lama'];

// ── Validasi input dasar ──────────────────────────────
if ($name === '' || $category === '' || $price < 0 || $stock < 0) {
    header("Location: edit.php?id=$id&msg=invalid");
    exit;
}

// ── Proses upload gambar (jika ada) ──────────────────
$image_path = $gambar_lama; // default: pakai gambar lama

if (!empty($_FILES['gambar']['name'])) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type     = mime_content_type($_FILES['gambar']['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        header("Location: edit.php?id=$id&msg=invalid_file");
        exit;
    }

    $dir_target = "gambar/";
    if (!is_dir($dir_target)) {
        mkdir($dir_target, 0755, true);
    }

    $ext        = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $safe_name  = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $image_path = $dir_target . $safe_name;

    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $image_path)) {
        header("Location: edit.php?id=$id&msg=upload_gagal");
        exit;
    }

    // Hapus gambar lama jika ada dan bukan URL eksternal
    if ($gambar_lama && strpos($gambar_lama, 'http') !== 0 && file_exists($gambar_lama)) {
        unlink($gambar_lama);
    }
}

// ── Update ke database dengan prepared statement ──────
$stmt = $conn->prepare("UPDATE products SET nama=?, kategori=?, harga=?, stok=?, gambar=? WHERE id=?");
$stmt->bind_param('ssiisi', $name, $category, $price, $stock, $image_path, $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: admin.php?msg=edit_ok");
} else {
    $stmt->close();
    header("Location: admin.php?msg=error");
}
exit;
?>
