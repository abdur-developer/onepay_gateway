<?php
$api_key = 'sk-655eaf31013827d48c3e56583b9cad3f';
$url = 'https://bot.rcrel.com/api/create.php';
$url = 'http://localhost:8080/getway/api/create.php';

$data = [
    'api_key'     => $api_key,
    'success_url' => 'https://yourdomain.com/success.php',
    'cancel_url'  => 'https://yourdomain.com/cancel.php',
    'my_data'     => 'my_data',
    'amount'      => 40
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
    header('Content-Type: application/json');
    echo $response;
    // $result = json_decode($response, true);
    // var_dump($result);
    exit();
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
