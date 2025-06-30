<?php

if (isset($_REQUEST['device_id']) && isset($_REQUEST['device_key'])) {
    $device_id = $_REQUEST['device_id'];
    $device_key = $_REQUEST['device_key'];

    require_once('dbcon.php'); // This must return a $conn MySQLi object

    // Prepare and execute the SELECT statement
    $stmt = $conn->prepare("SELECT id, app_id FROM users WHERE device = ? LIMIT 1");
    $stmt->bind_param("s", $device_key);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (empty($user['app_id'])) {
            $updateStmt = $conn->prepare("UPDATE users SET app_id = ? WHERE id = ?");
            $updateStmt->bind_param("si", $device_id, $user['id']);
            $updateStmt->execute();
            echo 'success';
        } elseif ($user['app_id'] === $device_id) {
            echo 'success';
        } else {
            echo 'Device already registered';
        }
    } else {
        echo 'User not found';
    }

} else {
    exit('Invalid request');
}
