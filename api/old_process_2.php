<?php
if(isset($_POST['trx'])){
    //rocket, nagad, bkash => method
    //trx => transaction id
    require_once('dbcon.php');
    $sql = "SELECT * FROM payment WHERE trx = '{$_POST['trx']}'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        header("Location: index.php", true, 303);
        exit;
    }
    $row = mysqli_fetch_assoc($result);
    if($row['isUser'] == 0 || $row['isUser'] == null){
        if($_POST['method'] == 'bkash'){
            $sql = "SELECT number FROM method WHERE id = '1'";
            
        }else if($_POST['method'] == 'nagad'){
            $sql = "SELECT number FROM method WHERE id = '2'";
            
        }else if($_POST['method'] == 'rocket'){
            $sql = "SELECT number FROM method WHERE id = '3'";
        }
        $method = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    }else{
        $sql = "SELECT nagad, bkash, rocket FROM buy_pack WHERE id = '{$row['isUser']}' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            header("Location: index.php", true, 303);
            exit;
        }
        $row = mysqli_fetch_assoc($result);
        $method = ["name" => $_POST['method'], "number" => $row[$_POST['method']]];
    }
    include('method-b/index.php');
}
?>
