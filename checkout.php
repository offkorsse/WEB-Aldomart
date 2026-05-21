<?php
/**
 * checkout.php
 * Endpoint AJAX: validasi stok & kurangi stok di database saat checkout.
 * Dipanggil dari script.js via fetch() POST dengan body JSON.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

// Harus login
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
    exit;
}

include 'koneksi.php';

// Baca body JSON
$body = json_decode(file_get_contents('php://input'), true);
$items = $body['items'] ?? [];

if (empty($items)) {
    echo json_encode(['ok' => false, 'message' => 'Keranjang kosong.']);
    exit;
}

// ── Mulai transaksi ──────────────────────────────────
$conn->begin_transaction();

$errors = [];

foreach ($items as $item) {
    $id  = (int)($item['id']  ?? 0);
    $qty = (int)($item['qty'] ?? 0);

    if ($id <= 0 || $qty <= 0) continue;

    // Ambil stok terkini dengan SELECT FOR UPDATE (kunci baris)
    $stmt = $conn->prepare("SELECT nama, stok FROM products WHERE id = ? FOR UPDATE");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row    = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $errors[] = "Produk ID $id tidak ditemukan.";
        continue;
    }

    if ($row['stok'] < $qty) {
        $errors[] = "Stok \"" . $row['nama'] . "\" tidak cukup. "
                  . "Tersedia: " . $row['stok'] . ", diminta: $qty.";
        continue;
    }

    // Kurangi stok
    $stmt2 = $conn->prepare("UPDATE products SET stok = stok - ? WHERE id = ? AND stok >= ?");
    $stmt2->bind_param('iii', $qty, $id, $qty);
    $stmt2->execute();

    if ($stmt2->affected_rows === 0) {
        $errors[] = "Stok \"" . $row['nama'] . "\" berubah saat proses. Silakan coba lagi.";
    }
    $stmt2->close();
}

if (!empty($errors)) {
    $conn->rollback();
    echo json_encode(['ok' => false, 'message' => implode(' ', $errors)]);
} else {
    $conn->commit();
    echo json_encode(['ok' => true, 'message' => 'Pesanan berhasil diproses!']);
}

$conn->close();
exit;
?>
