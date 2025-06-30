<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if(!isset($_GET['plan'])) {
    header('Location: home.php');
    exit();
}
$plan = $_GET['plan'];
$amount = 0;
$sql = "SELECT * FROM plans WHERE id = ? LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $plan, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    $amount = $result['price'];
} else {
    header('Location: home.php');
    exit();
}
$api_key = 'sk-admin123456&&_#'; // Change this to your actual API key
$url = $main_domain . '/api/create.php';

$data = [
    'api_key'     => $api_key,
    'success_url' => $main_domain . '/success.php',
    'cancel_url'  => $main_domain . '/failed.php',
    'my_data'     => $plan,
    'amount'      => $amount,
    'type'        => 'sandbox'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'User-Agent: Mozilla/5.0'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    if (isset($result['payment_url'])) {
        header('Location: ' . $result['payment_url'], true, 302);
        exit;
    } else {
        echo 'Invalid response: ' . $response;
    }
} else {
    echo "HTTP Error $http_code - Response: $response";
}
?>
// Prepare data for the API request