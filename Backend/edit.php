<?php
include 'koneksi.php';
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; margin-top: 5px; }
        .btn-submit { background: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Edit Produk</h2>
    <a href="admin.php">Kembali</a><br><br>

    <form action="proses_edit.php" method="POST" enctype="multipart/form-data" style="max-width: 400px;">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <input type="hidden" name="gambar_lama" value="<?php echo $data['image']; ?>">

        <div class="form-group">
            <label>Nama Produk:</label>
            <input type="text" name="name" value="<?php echo $data['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori:</label>
            <input type="text" name="category" value="<?php echo $data['category']; ?>" required>
        </div>
        <div class="form-group">
            <label>Harga (Angka saja):</label>
            <input type="number" name="price" value="<?php echo $data['price']; ?>" required>
        </div>
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stock" value="<?php echo $data['stock']; ?>" required>
        </div>
        <div class="form-group">
            <label>Gambar Saat Ini:</label><br>
            <img src="<?php echo $data['image']; ?>" width="80"><br><br>
            <label>Ganti Gambar (Opsional):</label><br>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn-submit">Update Produk</button>
    </form>
</body>
</html>
