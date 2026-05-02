<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Eksekusi hapus baris dari database
    $sql = "DELETE FROM products WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
    } else {
        echo "Error menghapus: " . $conn->error;
    }
}
?>
