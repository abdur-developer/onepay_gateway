<?php
if(isset($_POST['trx'])){
    //rocket, nagad, bkash => method
    //trx => transaction id
    require_once('dbcon.php');
    $sql = "SELECT * FROM payment WHERE trx = '{$_POST['trx']}'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        header("Location: index.php");
        exit;
    }
    $row = mysqli_fetch_assoc($result);

    if($_POST['method'] == 'bkash'){
        $sql = "SELECT * FROM method WHERE id = '1'";
        
    }else if($_POST['method'] == 'nagad'){
        $sql = "SELECT * FROM method WHERE id = '2'";
        
    }else if($_POST['method'] == 'rocket'){
        $sql = "SELECT * FROM method WHERE id = '3'";
    }
    $method = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    include('method-a/index.php');
}
?>
