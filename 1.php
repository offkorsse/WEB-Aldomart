<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

$conn = new mysqli("localhost", "root", "", "aldomart_db"); 

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal"]));
}

$sql = "SELECT * FROM products"; 
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            "nama"  => $row["nama"],
            "harga" => "Rp " . number_format($row["harga"], 0, ',', '.'),
            "stok" => $row["stok"], 
            "kategori"   => $row["kategori"], 
            "gambar" => $row["gambar"]
        ];
    }
}

echo json_encode($data);
$conn->close();
?>
