<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $nama_file = $_FILES['image']['name'];
    $tmp_file  = $_FILES['image']['tmp_name'];
    
    $dir_target = "gambar/";
    
    if (!is_dir($dir_target)) {
        mkdir($dir_target, 0777, true);
    }
    
    $path = $dir_target . time() . "_" . basename($nama_file); 
    
    if (move_uploaded_file($tmp_file, $path)) {
        $sql = "INSERT INTO products (name, price, stock, category, image) VALUES ('$name', '$price', '$stock', '$category', '$path')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar. Pastikan folder memiliki izin akses yang benar.";
    }
}
?>