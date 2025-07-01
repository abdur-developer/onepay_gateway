<?php
    include_once "../config.php";
    $input = json_decode(file_get_contents("php://input"), true);
    $buy_id = $input['buy_id'] ?? null;
    $methods = $input['methods'] ?? [];

    header('Content-Type: application/json');
    if (!$buy_id || !is_array($methods) || empty($methods)) {
        echo json_encode(["success" => false, "message" => "Invalid input"]);
        exit;
    }
    if($buy_id == "admin"){
        for($i = 0; $i < 3; $i++){
            $sql = "UPDATE method SET number = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$methods[$i], $i + 1]);
        }
    }else{
        $stmt = $pdo->prepare("UPDATE buy_pack SET bkash = ?, nagad = ?, rocket = ? WHERE id = ?");
        $stmt->execute([$methods[0],$methods[1],$methods[2],$buy_id]);
    }

    echo json_encode(["success" => true]);