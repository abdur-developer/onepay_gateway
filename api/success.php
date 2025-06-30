<?php
    $trx = $_GET['trx'] ?? null;
    if ($trx === null) {
        header("Location: index.php", true, 303);
        exit;
    }
    require_once('dbcon.php');
    $sql = "SELECT success_url, my_data FROM payment WHERE trx = '$trx'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        header("Location: index.php", true, 303);
        exit;
    }
    $row = mysqli_fetch_assoc($result);
    $sql = "DELETE FROM payment WHERE trx = '$trx'";
    mysqli_query($conn, $sql);
    
    header("location: {$row['success_url']}?my_data={$row['my_data']}", true, 303);    
    exit;

?>