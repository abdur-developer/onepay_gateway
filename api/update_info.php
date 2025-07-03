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

        if(in_array($senderNum, ["bKash", "Bkash Personal", "bKash Agent", "bKash Merchant"])) {
            if (preg_match('/Tk\s+([\d,]+\.\d{2})\s+(?:received|payment).*?from\s+(\d{11}).*?TrxID[:\s]+([A-Z0-9]+)/i', $message, $matches)) {
                $amount = str_replace(',', '', $matches[1]);
                $number = $matches[2];
                $trxid  = $matches[3];
            }
        }
        else if(in_array($senderNum, ["Rocket Personal", "Rocket Agent", "Rocket Merchant"])) {
            if (preg_match('/Tk\s+([\d,]+\.\d{2})\s+(?:sent to|paid to Merchant|received from (?:Agent )?)\s*(\d{11}).*?TrxID[:\s]+([A-Z0-9]+)/i', $message, $matches)) {
                $amount = str_replace(',', '', $matches[1]);
                $number = $matches[2];
                $trxid  = $matches[3];
            }
        }
        else if(in_array($senderNum, ["NAGAD", "Nagad Personal", "Nagad Merchant"])) {
            // English format (Merchant)
            if (preg_match('/Tk\s+([\d,]+\.\d{2})\s+has been successfully paid to Merchant\s+(\d{11}).*?TrxID[:\s]+([A-Z0-9]+)/i', $message, $matches)) {
                $amount = str_replace(',', '', $matches[1]);
                $number = $matches[2];
                $trxid  = $matches[3];
            }
            // Bengali format (Agent)
            else if (preg_match('/Tk\s+([\d,]+\.\d{2})\s+পেয়েছেন Agent\s+(\d{11}).*?TrxID[:：]\s*([A-Z0-9]+)/u', $message, $matches)) {
                $amount = str_replace(',', '', $matches[1]);
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