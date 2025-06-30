<?php
require '../config.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageData = json_decode(file_get_contents('php://input'), true);
    $message = '';
    /*
    getting => id name description badgeName validityDays price
    db structure => id name description price duration badgeName

    */
    if($packageData['id'] == null){
        $message = 'Creating a new package';
        // If the package ID is null, it means we are creating a new package
        $stmt = $pdo->prepare("INSERT INTO plans (name, description, badgeName, web_limit, status, duration, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$packageData['name'], $packageData['description'], $packageData['badgeName'], $packageData['web_limit'], 'active', $packageData['validityDays'], $packageData['price']]);
        $packageId = $pdo->lastInsertId();
    } else {
        $message = 'Updating an existing package';
        // If the package ID is not null, we are updating an existing package
        $stmt = $pdo->prepare("UPDATE plans SET name = ?, description = ?, badgeName = ?, web_limit = ?, duration = ?, price = ? WHERE id = ?");
        $stmt->execute([$packageData['name'], $packageData['description'], $packageData['badgeName'], $packageData['web_limit'], $packageData['validityDays'], $packageData['price'], $packageData['id']]);
        $packageId = $packageData['id'];
    }

    // Send a JSON response
    echo json_encode(['success' => true, 'message' => $message, 'packageId' => $packageId ?? null]);
    exit;
}else {
    // If the request method is not POST, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}