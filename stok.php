<?php
/**
 * stok.php
 * Endpoint AJAX: kembalikan stok terkini untuk daftar id produk.
 * GET  ?ids=1,2,3   → { "1": 20, "2": 0, "3": 5 }
 * Dipakai script.js untuk validasi stok sebelum tambah & saat render.
 */
header('Content-Type: application/json');
header('Cache-Control: no-store'); // jangan di-cache browser

session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include 'koneksi.php';

// Ambil semua produk (tanpa filter id) jika tidak ada param ids
$rawIds = trim($_GET['ids'] ?? '');

if ($rawIds === '') {
    // Kembalikan seluruh stok produk (untuk inisialisasi halaman)
    $result = $conn->query("SELECT id, stok FROM products");
    $data   = [];
    while ($row = $result->fetch_assoc()) {
        $data[(int)$row['id']] = (int)$row['stok'];
    }
    echo json_encode($data);
    $conn->close();
    exit;
}

// Sanitasi: hanya integer yang valid
$ids = array_filter(
    array_map('intval', explode(',', $rawIds)),
    fn($v) => $v > 0
);

if (empty($ids)) {
    echo json_encode([]);
    exit;
}

// Buat placeholder (?,?,?) sesuai jumlah id
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types        = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT id, stok FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[(int)$row['id']] = (int)$row['stok'];
}
$stmt->close();
$conn->close();

echo json_encode($data);
exit;
?>
