<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; margin-top: 5px; }
        .btn-submit { background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Tambah Produk Baru</h2>
    <a href="admin.php">Kembali</a><br><br>

    <form action="proses_tambah.php" method="POST" enctype="multipart/form-data" style="max-width: 400px;">
        <div class="form-group">
            <label>Nama Produk:</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Kategori:</label>
            <select name="category" required>
                <option value="Minuman">Minuman</option>
                <option value="Minuman Ringan">Minuman Ringan</option>
                <option value="Minuman Teh">Minuman Teh</option>
                <option value="Permen">Permen</option>
                <option value="Snack">Snack</option>
                <option value="Makanan">Makanan</option>
                <option value="Kecantikan">Kecantikan</option>
                <option value="Perawatan">Perawatan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga (Angka saja):</label>
            <input type="number" name="price" required>
        </div>
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stock" required>
        </div>
        <div class="form-group">
            <label>Upload Gambar:</label><br>
            <input type="file" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn-submit">Simpan Produk</button>
    </form>
</body>
</html>
