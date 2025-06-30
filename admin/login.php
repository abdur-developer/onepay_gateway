<?php
    require '../config.php';
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $user['name'] ?? "Admin Login";
        $_SESSION['user_email'] = $user['email'] ?? "admin login koreche";
        $_SESSION['password'] = "abdurrahman_01709409266";

        redirect('../home.php');
    }
?>