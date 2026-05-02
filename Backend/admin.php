<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin ALDOMART</title>
    <style>
        body { font-family: sans-serif; background: #f4f5f7; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #e8192c; color: white; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; color: white; font-size: 14px; }
        .btn-tambah { background-color: #28a745; display: inline-block; margin-bottom: 10px; }
        .btn-edit { background-color: #007bff; margin-right: 5px; }
        .btn-hapus { background-color: #dc3545; }
        img { width: 60px; height: 60px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Admin ALDOMART</h2>
        <a href="tambah.php" class="btn btn-tambah">+ Tambah Produk Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM products ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td><img src='" . $row['image'] . "' alt='gambar'></td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
                        echo "<td>" . $row['stock'] . "</td>";
                        echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>Edit</a>
                                <a href='proses_hapus.php?id=" . $row['id'] . "' class='btn btn-hapus' onclick='return confirm(\"Yakin ingin menghapus " . $row['name'] . "?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>Belum ada produk.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
