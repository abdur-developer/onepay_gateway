<?php
session_start();
header('Content-Type: text/plain');
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$inputTrx = isset($_POST['input-trx']) ? trim($_POST['input-trx']) : '';
$postTrx = isset($_POST['post-trx']) ? trim($_POST['post-trx']) : '';

if (empty($inputTrx) || empty($postTrx)) {
    http_response_code(400);
    echo 'Missing data';
    exit;
}

$stmt1 = $conn->prepare("SELECT amount, type FROM payment WHERE trx = ? AND status = '0' LIMIT 1");
$stmt1->bind_param("s", $postTrx);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows === 1) {
    $row = $result1->fetch_assoc();
    if ($row['type'] == 'sandbox') {
        echo 'success';
        $stmt1->close();
        $conn->close();
        exit;
    }else{
        $stmt2 = $conn->prepare("SELECT id FROM sms WHERE trx = ? AND amount = ? LIMIT 1");
        $stmt2->bind_param("sd", $inputTrx, $row['amount']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        if ($result2->num_rows === 1) {
            $row2 = $result2->fetch_assoc();
            $sql = "DELETE FROM sms WHERE id = {$row2['id']}";
            mysqli_query($conn, $sql);
            echo 'success';
        } else {
            echo 'invalid';
        }
        $stmt2->close();
        $stmt1->close();
        $conn->close();
        exit;
    }
} else {
    echo 'not_found';
}

$stmt1->close();
$conn->close();
?>
