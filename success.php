<?php 
if(isset($_GET['my_data'])) {
    $api = "sk-".bin2hex(random_bytes(16));
    include_once('config.php');

    date_default_timezone_set('Asia/Dhaka');
    $time = date('Y-m-d');

    $sql = "INSERT INTO buy_pack (user_id, pack_id, api, time) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $_GET['my_data'], $api, $time]);

    $sql = "SELECT device FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $device = $stmt->fetch()['device'];
    if($device == null || $device == '') {
        $device = "device-".bin2hex(random_bytes(8));
        $sql = "UPDATE users SET device = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$device, $_SESSION['user_id']]);
    }


}
header('Location: home.php');
exit();
?>
