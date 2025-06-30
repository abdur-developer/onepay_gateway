<?php
    include_once "../config.php";
    $input = json_decode(file_get_contents("php://input"), true);
    $buy_id = $input['buy_id'] ?? null;
    $domains = $input['domains'] ?? [];

    header('Content-Type: application/json');
    if (!$buy_id || !is_array($domains) || empty($domains)) {
        echo json_encode(["success" => false, "message" => "Invalid input"]);
        exit;
    }

    // Convert domains to JSON string
    $domains_json = json_encode($domains, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("UPDATE buy_pack SET domain_list = ? WHERE id = ?");
    $stmt->execute([$domains_json, $buy_id]);

    echo json_encode(["success" => true]);