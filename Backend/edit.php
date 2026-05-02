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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - ALDOMART</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: #f4f5f7; 
            color: #222; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background: #ffffff;
            width: 100%;
            max-width: 550px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }

        .form-header {
            margin-bottom: 24px;
            text-align: center;
        }
        .form-header h2 {
            font-size: 24px;
            color: #222;
            font-weight: 700;
        }
        .form-header p {
            color: #888;
            font-size: 14px;
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="text"], 
        input[type="number"], 
        select {
            width: 100%;
            padding: 12px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #222;
            background: #fdfdfd;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.2s ease;
            outline: none;
        }
        
        input:focus, select:focus {
            border-color: #007bff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        }

        .current-img-box {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .current-img-box img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 6px;
            background: #fff;
            padding: 4px;
            border: 1px solid #ddd;
        }
        .current-img-box span {
            font-size: 13px;
            color: #555;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 13px;
            color: #555;
            background: #fdfdfd;
            border: 1.5px dashed #ccc;
            border-radius: 8px;
            cursor: pointer;
        }
        input[type="file"]::file-selector-button {
            background: #f0f0f0;
            color: #333;
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }
        input[type="file"]::file-selector-button:hover {
            background: #e0e0e0;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-submit {
            background: #007bff;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        .btn-submit:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .btn-back {
            background: #f4f5f7;
            color: #555;
            border: 1.5px solid #ddd;
        }
        .btn-back:hover {
            background: #e9eaec;
            color: #222;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <div class="form-header">
            <h2>Edit Produk</h2>
            <p>Perbarui informasi produk di etalase ALDOMART</p>
        </div>

        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $data['image']; ?>">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category" required>
                    <?php
                    $kategori_list = ["Minuman", "Minuman Ringan", "Minuman Teh", "Permen", "Snack", "Makanan", "Kecantikan", "Perawatan"];
                    foreach ($kategori_list as $kat) {
                        $selected = ($data['category'] == $kat) ? "selected" : "";
                        echo "<option value='$kat' $selected>$kat</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" value="<?php echo $data['price']; ?>" min="0" required>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Stok Tersedia</label>
                    <input type="number" name="stock" value="<?php echo $data['stock']; ?>" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Gambar Saat Ini</label>
                <div class="current-img-box">
                    <img src="<?php echo htmlspecialchars($data['image']); ?>" alt="Gambar Produk">
                    <span>Biarkan kolom di bawah kosong jika tidak ingin mengganti gambar.</span>
                </div>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="action-buttons">
                <a href="admin.php" class="btn btn-back">Batal & Kembali</a>
                <button type="submit" class="btn btn-submit">Update Produk</button>
            </div>

        </form>
    </div>

</body>
</html>