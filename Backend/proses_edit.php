<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $gambar_lama = $_POST['gambar_lama'];

    if ($_FILES['image']['name'] != "") {
        $nama_file = $_FILES['image']['name'];
        $tmp_file  = $_FILES['image']['tmp_name'];
        $path = "images/" . time() . "_" . basename($nama_file);
        
        move_uploaded_file($tmp_file, $path);
        
        $sql = "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock', image='$path' WHERE id='$id'";
    } else {
        $sql = "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
