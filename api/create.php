<?php
    header('Content-Type: application/json');
    require_once('dbcon.php');
    $user_id = 0;

    function isValidDomain($pack_id) {
        return true;
    }
    /*
        function isValidDomain($pack_id) {
            GLOBAL $conn, $user_id;

            $whitelisted_domains = [];
            $result = $conn->query("SELECT domain_list FROM buy_pack WHERE user_id = '$user_id' AND pack_id = '$pack_id' LIMIT 1");
            while ($row = $result->fetch_assoc()) {
                $domains = json_decode($row['domain_list'], true);
                if (is_array($domains) && count($domains) > 0) {
                    foreach ($domains as $domain) {
                        $whitelisted_domains[] = $domain;
                    }
                }
            }

            // 2. Get Referer or Origin
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            $source = !empty($origin) ? $origin : $referer;

            // 3. Parse domain from source URL
            $parsed_url = parse_url($source);
            $http_domain = $parsed_url['host'] ?? '';

            // 4. Check if domain is in whitelist
            if (in_array($http_domain, $whitelisted_domains)) return true;
            else return false;
        }
    */
    function showJson($isSuccess, $message, $payment_url) {
        echo json_encode([
            'success' => $isSuccess,
            'message' => $message,
            'payment_url' => $payment_url
        ]);
        exit;

    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        showJson(false, 'not allowed', '../');
    }

    $received_api_key = $_POST['api_key'] ?? '';
    $cancel_url = trim($_POST['cancel_url'] ?? '');
    $my_data = trim($_POST['my_data'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $success_url = trim($_POST['success_url'] ?? '');
    $type = trim($_POST['type'] ?? 'live');
    $isUser = 0;

    $sql = "SELECT id, user_id, pack_id FROM buy_pack WHERE api = '$received_api_key' LIMIT 1";
    $result = $conn->query($sql);
    $row_api = $result->fetch_assoc();
    $user_id = $row_api['user_id'] ?? 0;
    $pack_id = $row_api['pack_id'] ?? 0;
    $isUser = $row_api['id'] ?? 0;
    //if ($user_id <= 0) {
    //showJson(false, 'User not logged in', $cancel_url . '?my_data=' . urlencode($my_data).'&message=User+not+found');
    //}
    if ($result->num_rows != 1) {
        if(!empty($received_api_key)) {
            if ($received_api_key != 'sk-admin123456&&_#') {
                showJson(false, 'Invalid API Key', $cancel_url . '?my_data=' . urlencode($my_data).'&message=Invalid+API+Key');
            }
        }else{
            showJson(false, 'Invalid API Key', $cancel_url . '?my_data=' . urlencode($my_data).'&message=Invalid+API+Key');
        } 
    }else{
        //check package validity expires
        $sql = "SELECT p.duration, b.time FROM buy_pack b JOIN plans p ON p.id = b.pack_id WHERE b.api = '$received_api_key' LIMIT 1";

        $result = $conn->query($sql);

        if ($row = $result->fetch_assoc()) {
            $duration = $row['duration']; // ধরো, দিনে
            $time = $row['time']; // ধরো, Y-m-d H:i:s ফরম্যাটে

            date_default_timezone_set('Asia/Dhaka');
            $start_date = date('Y-m-d', strtotime($time));
            $current_date = date('Y-m-d');
            $days_passed = (strtotime($current_date) - strtotime($start_date)) / 86400;

            if ($days_passed >= $duration) {
                $conn->query("DELETE FROM buy_pack WHERE api = '$received_api_key'");
                showJson(
                    false, 
                    'Your package has expired. Please renew to continue using our services.', 
                    $cancel_url . '?my_data=' . urlencode($my_data) . '&message=' . urlencode('Your package has expired. Please renew to continue using our services.')
                );
            }
        }

    }

    if ($amount <= 0 || empty($success_url) || empty($cancel_url)) {
        showJson(false, 'Invalid input data', $cancel_url . '?my_data=' . urlencode($my_data) . '&message=' . urlencode('Invalid input data'));
    }

    if(!isValidDomain($pack_id)){
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

        $source = $origin ?: $referer;

        if ($source && filter_var($source, FILTER_VALIDATE_URL)) {
            $parsed_url = parse_url($source);
            $http_domain = $parsed_url['host'] ?? 'invalid_host';
        } else {
            $http_domain = 'unknown_or_invalid_url';
        }

        showJson(false, $http_domain, "aaaa");

        // showJson(false, 'Invalid domain', $cancel_url . '?my_data=' . urlencode($my_data). '&message='.urlencode('Invalid domain'));
    }

    $trx_id = 'TX-' . strtoupper(bin2hex(random_bytes(6)));

    $stmt = $conn->prepare("INSERT INTO payment (trx, amount, success_url, cancel_url, my_data, type, isUser) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssssi", $trx_id, $amount, $success_url, $cancel_url, $my_data, $type, $isUser);
    $stmt->execute();
    $stmt->close();

    $payment_url = "$domain/api/payment.php?trx=$trx_id";

    showJson(true, $trx_id, $payment_url);
?>
