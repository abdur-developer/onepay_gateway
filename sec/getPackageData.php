<?php

$ids = file_get_contents('php://input');
$id = json_decode($ids, true)['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Package ID is required']);
    exit;
}
require_once '../config.php';
$sql = "SELECT * FROM plans WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Package not found']);
}
// End of file getPackageData.php
