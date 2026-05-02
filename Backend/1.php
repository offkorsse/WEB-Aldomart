<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'aldomart_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $formatted_price = "Rp " . number_format($row['price'], 0, ',', '.');
        
        $products[] = [
            'id'    => $row['id'],
            'name'  => $row['name'],
            'price' => $formatted_price,
            'cat'   => $row['category'],
            'image' => $row['image']
        ];
    }
}

echo json_encode($products);

$conn->close();
?>
