<?php 
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$password = $data['password'];
$senderNum = $data['senderNum'];
$message = $data['message'];
$from = $data['from'];
$temp = array();
// "Bkash Personal", "Nagad Personal", "Rocket Personal"
if($password == '1234567890' ){
    $amount = "";
    $number = "";
    $trxid  = "";
    require_once('dbcon.php');
    $sql = "SELECT COUNT(*) as count FROM sms WHERE message = '$message'";
    $ooo = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    if($ooo['count'] == 0){
        if("bKash" == $senderNum || "Bkash Personal" == $senderNum){
            preg_match('/Tk (\d+\.\d{2}) from (\d{11}).*?TrxID ([A-Z0-9]+)/', $message, $matches);
            if ($matches) {
                $amount = $matches[1];
                $number = $matches[2];
                $trxid  = $matches[3];
            }
        }else if("NAGAD" == $senderNum || "Nagad Personal" == $senderNum){
            preg_match('/Amount: Tk (\d+\.\d{2})\s+Sender: (\d{11})\s+Ref:.*?\s+TxnID: ([A-Z0-9]+)/s', $message, $matches);
            if ($matches) {
                $amount = $matches[1];
                $number = $matches[2];
                $trxid  = $matches[3];
            }
        }
        
        if($amount != "" && $number != "" && $trxid  != ""){
            
            $sql = "INSERT INTO sms (name, message, m_from, amount, number, trx) VALUES ('$senderNum', '$message', '$from', '$amount', '$number','$trxid');";
            $result = mysqli_query($conn, $sql);
            
            $temp['type'] = "success";
            $temp['message'] = 'SMS was sent to server!!';
            mysqli_close($conn);
        }else{
            $temp['type'] = "error";
            $temp['message'] = 'data empty';
        }
    
    }else{
        $temp['type'] = "error";
        $temp['message'] = 'Message already save';
    }
    
}else{
    $temp['type'] = "error";
    $temp['message'] = 'password error';
}
echo json_encode($temp);
?>