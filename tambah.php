<?php
session_start();
include 'auth_check.php'; // proteksi: harus login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - ALDOMART</title>
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
            max-width: 500px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }
        .form-header { margin-bottom: 24px; text-align: center; }
        .form-header h2 { font-size: 24px; color: #222; font-weight: 700; }
        .form-header p  { color: #888; font-size: 14px; margin-top: 6px; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 700;
            color: #555; margin-bottom: 8px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%; padding: 12px 16px;
            font-family: 'DM Sans', sans-serif; font-size: 14px; color: #222;
            background: #fdfdfd; border: 1.5px solid #e0e0e0;
            border-radius: 8px; transition: all 0.2s ease; outline: none;
        }
        input:focus, select:focus {
            border-color: #e8192c; background: #fff;
            box-shadow: 0 0 0 4px rgba(232, 25, 44, 0.1);
        }
        input[type="file"] {
            width: 100%; padding: 10px; font-size: 13px; color: #555;
            background: #f9f9f9; border: 1.5px dashed #ccc;
            border-radius: 8px; cursor: pointer;
        }
        input[type="file"]::file-selector-button {
            background: #e8192c; color: white; padding: 6px 12px;
            border: none; border-radius: 4px; margin-right: 12px;
            cursor: pointer; font-weight: 600; transition: background 0.2s;
        }
        input[type="file"]::file-selector-button:hover { background: #c0121f; }
        .action-buttons { display: flex; gap: 12px; margin-top: 32px; }
        .btn {
            flex: 1; padding: 12px; font-size: 14px; font-weight: 700;
            font-family: 'DM Sans', sans-serif; border: none; border-radius: 8px;
            cursor: pointer; text-align: center; text-decoration: none; transition: all 0.2s;
        }
        .btn-submit {
            background: #e8192c; color: white;
            box-shadow: 0 4px 12px rgba(232, 25, 44, 0.2);
        }
        .btn-submit:hover { background: #c0121f; transform: translateY(-2px); }
        .btn-back { background: #f4f5f7; color: #555; border: 1.5px solid #ddd; }
        .btn-back:hover { background: #e9eaec; color: #222; }
        .error-msg {
            background: #fdf0f1; color: #e8192c;
            border: 1px solid #fad3d6; border-radius: 8px;
            padding: 10px 14px; font-size: 13px; margin-bottom: 20px;
        }
        .file-hint { font-size: 11.5px; color: #999; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Tambah Produk</h2>
            <p>Masukkan detail produk baru ke dalam etalase</p>
        </div>

        <?php
        $errMap = [
            'invalid'      => 'Data tidak valid. Periksa kembali isian form.',
            'no_file'      => 'Gambar produk wajib diupload.',
            'invalid_file' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.',
            'upload_gagal' => 'Gagal mengupload gambar. Periksa izin folder.',
        ];
        $errKey = $_GET['msg'] ?? '';
        if (isset($errMap[$errKey])):
        ?>
            <div class="error-msg">⚠️ <?= htmlspecialchars($errMap[$errKey]) ?></div>
        <?php endif; ?>

        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" placeholder="Contoh: Coca-Cola 330ml" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                    <option value="Sabun">Sabun</option>
                    <option value="Lain-lainnya">Lain-lainnya</option>
                </select>
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 15000" min="0" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" placeholder="Contoh: 50" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Upload Gambar Produk</label>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/gif,image/webp" required>
                <p class="file-hint">Format: JPG, PNG, GIF, WEBP. Maks 2MB.</p>
            </div>

            <div class="action-buttons">
                <a href="admin.php" class="btn btn-back">Batal & Kembali</a>
                <button type="submit" class="btn btn-submit">Simpan Produk</button>
            </div>
        </form>
    </div>
</body>
</html>
