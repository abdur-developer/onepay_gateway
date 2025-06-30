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
        $sql = "SELECT number FROM method WHERE id = '1'";
        $method = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        include('method/bkash.php');
        
    }else if($_POST['method'] == 'nagad'){
        $sql = "SELECT number FROM method WHERE id = '2'";
        $method = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        include('method/nagad.php');
        
    }else if($_POST['method'] == 'rocket'){
        include('method/rocket.php');
    }
}
