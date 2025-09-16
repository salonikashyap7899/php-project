<?php
require 'db.php';

header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$q = trim($q);

if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, name, price, image, description 
    FROM products 
    WHERE name LIKE ? 
       OR description LIKE ?
    ORDER BY created_at DESC
    LIMIT 20
");

$searchTerm = "%$q%";
$stmt->execute([$searchTerm, $searchTerm]);
$results = $stmt->fetchAll();

echo json_encode($results);
