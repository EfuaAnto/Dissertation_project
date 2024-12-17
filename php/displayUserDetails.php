<?php

session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connectionScript.php';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Fetch user details
    $sql = "SELECT * FROM user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $details = $result->fetch_assoc();
        $userData = [
            "user_id" => $details["user_id"] ?? null,
            "email" => $details["email"] ?? "",
            "name" => $details["name"] ?? "",
            "surname" => $details["surname"] ?? "",
            "username" => $details["username"] ?? "",
            "password" => $details["password"] ?? "",
            "height" => $details["height"] ?? "0.00",
            "age" => $details["age"] ?? 0,
            "target_weight" => $details["target_weight"] ?? "0.00",
            "health_Condition" => $details["health_Condition"] ?? "",
        ];

        // Fetch weight details
        $sql2 = "SELECT weight FROM weight_recorded WHERE user_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $weightDetails = $result2->fetch_assoc();
            $userData["weight"] = $weightDetails["weight"] ?? "0.00";
        } 

        // Fetch user preferences
        $sql3 = "SELECT * FROM user_preferences WHERE user_id = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();

        if ($result3->num_rows > 0) {
            $preferenceDetails = $result3->fetch_assoc();
            $userData["milestone_alert"] = $preferenceDetails["milestone_alert"] ?? "";
            $userData["inactivity_reminder"] = $preferenceDetails["inactivity_reminder"] ?? "";
            $userData["weight_fluctuation"] = $preferenceDetails["weight_fluctuation"] ?? "";
        } 

     
        echo json_encode(['success' => true, 'data' => $userData]);
    } else {
        echo json_encode(["success" => false, "error" => "User not found"]);
    }

    
    $stmt->close();
    $stmt2->close();
   $stmt3->close();
} else {
    echo json_encode(["success" => false, "error" => "User ID not provided"]);
}

$conn->close();

?>