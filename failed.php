<?php
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'An error occurred';
    header('Location: home.php?error='.urlencode($message));
    exit();
