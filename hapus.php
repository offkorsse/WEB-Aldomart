<?php
session_start();
include 'auth_check.php'; // proteksi: harus login
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // cast ke integer, cegah SQL injection

    // Gunakan prepared statement
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin.php?msg=hapus_ok");
    } else {
        $stmt->close();
        header("Location: admin.php?msg=error");
    }
} else {
    header("Location: admin.php");
}
exit;
?>
