<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $nama_file = $_FILES['image']['name'];
    $tmp_file  = $_FILES['image']['tmp_name'];
    
    $path = "images/" . time() . "_" . basename($nama_file);

    if (move_uploaded_file($tmp_file, $path)) {
        $sql = "INSERT INTO products (name, price, stock, category, image) VALUES ('$name', '$price', '$stock', '$category', '$path')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: admin.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>
