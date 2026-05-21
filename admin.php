<?php
session_start();
include 'auth_check.php'; // proteksi: harus login
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin ALDOMART</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: #f4f5f7; 
            color: #222; 
            padding: 40px 20px; 
        }

        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 16px; 
            box-shadow: 0 4px 24px rgba(0,0,0,0.06); 
        }

        .header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .header-action h2 {
            font-size: 22px;
            font-weight: 700;
            color: #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-action h2::before {
            content: '';
            display: inline-block;
            width: 12px;
            height: 24px;
            background: #e8192c;
            border-radius: 4px;
        }

        .header-title-group {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .btn-home {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #f4f5f7;
            color: #555;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #e0e0e0;
        }
        .btn-home:hover {
            background: #e8192c;
            color: white;
            border-color: #e8192c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(232, 25, 44, 0.2);
        }

        .header-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            padding: 8px 16px; 
            border-radius: 8px; 
            font-size: 13px; 
            font-weight: 700; 
            text-decoration: none; 
            transition: all 0.2s ease; 
            border: none; 
            cursor: pointer; 
        }

        .btn-tambah { 
            background-color: #e8192c; 
            color: white; 
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(232, 25, 44, 0.2); 
        }
        .btn-tambah:hover { 
            background-color: #c0121f; 
            transform: translateY(-2px); 
            box-shadow: 0 6px 16px rgba(232, 25, 44, 0.3); 
        }
        .btn-logout-admin {
            background: #f4f5f7;
            color: #555;
            border: 1.5px solid #ddd;
            padding: 10px 16px;
        }
        .btn-logout-admin:hover {
            border-color: #e8192c;
            color: #e8192c;
            background: #fdf0f1;
        }

        .action-group {
            display: flex;
            gap: 8px;
        }
        .btn-edit { 
            background-color: #ebf5ff; 
            color: #0066cc; 
        }
        .btn-edit:hover { 
            background-color: #d1e7ff; 
        }
        .btn-hapus { 
            background-color: #fdf0f1; 
            color: #e8192c; 
        }
        .btn-hapus:hover { 
            background-color: #fad3d6; 
        }

        .table-responsive {
            overflow-x: auto;
        }
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            min-width: 800px;
        }
        th, td { 
            padding: 16px; 
            text-align: left; 
            border-bottom: 1px solid #f0f0f0; 
            vertical-align: middle;
        }
        th { 
            background-color: #fafafa; 
            color: #888; 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        th:last-child  { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        
        tbody tr { transition: background-color 0.15s ease; }
        tbody tr:hover { background-color: #fdfdfd; }

        .product-name { font-weight: 700; color: #222; font-size: 14px; }
        .badge-cat {
            background: #f4f5f7; color: #555;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
        }
        .price  { font-weight: 700; color: #e8192c; }
        .stock  { font-weight: 700; color: #222; }
        .stock-low { font-weight: 700; color: #e8192c; }

        .img-wrapper {
            width: 60px; height: 60px;
            background: #f9f9f9; border-radius: 8px;
            border: 1px solid #f0f0f0;
            display: flex; align-items: center; justify-content: center; padding: 4px;
        }
        .img-wrapper img { 
            max-width: 100%; max-height: 100%;
            object-fit: contain; border-radius: 4px;
        }

        /* Toast notifikasi */
        .toast {
            position: fixed; bottom: 24px; right: 24px;
            background: #222; color: #fff;
            padding: 12px 20px; border-radius: 10px;
            font-size: 14px; font-weight: 500;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease; pointer-events: none;
            z-index: 999;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: #15803D; }
        .toast.error   { background: #e8192c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-action">
            <div class="header-title-group">
                <a href="menu.php" class="btn-home" title="Kembali ke Halaman Pelanggan">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </a>
                <h2>Dashboard Admin ALDOMART</h2>
            </div>

            <div class="header-actions-right">
                <a href="tambah.php" class="btn btn-tambah">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Produk
                </a>
            </div>
        </div>

        <?php
        // Tampilkan pesan sukses/error dari redirect
        if (isset($_GET['msg'])):
            $msgMap = [
                'tambah_ok' => ['✅ Produk berhasil ditambahkan.', 'success'],
                'edit_ok'   => ['✅ Produk berhasil diperbarui.',  'success'],
                'hapus_ok'  => ['🗑️ Produk berhasil dihapus.',    'success'],
                'error'     => ['❌ Terjadi kesalahan.',           'error'],
            ];
            $msg = $msgMap[$_GET['msg']] ?? null;
            if ($msg):
        ?>
            <div style="padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; font-weight:600;
                        background:<?= $msg[1]==='success' ? '#f0fdf4' : '#fdf0f1' ?>;
                        color:<?= $msg[1]==='success' ? '#15803D' : '#e8192c' ?>;
                        border:1px solid <?= $msg[1]==='success' ? '#bbf7d0' : '#fad3d6' ?>;">
                <?= htmlspecialchars($msg[0]) ?>
            </div>
        <?php endif; endif; ?>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql    = "SELECT * FROM products ORDER BY id ASC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $stockClass = ($row['stok'] <= 5) ? 'stock-low' : 'stock';
                            echo "<tr>";
                            echo "<td style='color:#888; font-weight:500;'>" . (int)$row['id'] . "</td>";
                            echo "<td><div class='img-wrapper'><img src='" . htmlspecialchars($row['gambar']) . "' alt='gambar' onerror=\"this.src='https://placehold.co/60x60?text=?'\"></div></td>";
                            echo "<td><div class='product-name'>" . htmlspecialchars($row['nama']) . "</div></td>";
                            echo "<td><span class='badge-cat'>" . htmlspecialchars($row['kategori']) . "</span></td>";
                            echo "<td class='price'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                            echo "<td class='$stockClass'>" . (int)$row['stok'] . ($row['stok'] <= 5 ? ' ⚠️' : '') . "</td>";
                            echo "<td>
                                    <div class='action-group' style='justify-content:center;'>
                                        <a href='edit.php?id=" . (int)$row['id'] . "' class='btn btn-edit'>Edit</a>
                                        <a href='hapus.php?id=" . (int)$row['id'] . "' class='btn btn-hapus'
                                           onclick='return confirm(\"Yakin ingin menghapus " . htmlspecialchars($row['nama'], ENT_QUOTES) . "?\")'>Hapus</a>
                                    </div>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center; padding:40px; color:#888;'>Belum ada produk yang ditambahkan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
