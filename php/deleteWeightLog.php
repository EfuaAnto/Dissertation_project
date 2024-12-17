<?php

session_start();
header('Content-Type: application/json');
require 'connectionScript.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($data['weight_id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Missing weight_id."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $weight_id = $data['weight_id'];

    // Prepare and execute the delete query
    $query = "DELETE FROM weight_recorded WHERE user_id=? AND weight_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $weight_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Deleted successfully."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to delete: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Method not allowed."]);
}

$conn->close();


/*session_start();
header('Content-Type: application/json');
require 'connectionScript.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id'] ;
    $weight_id = $data['weight_id'];
   

    $query = "DELETE FROM weight_recorded WHERE user_id=? AND weight_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $weight_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => " deleted successfully."]);
    } else {
        echo json_encode(["error" => "Failed to delete : " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}

$conn->close();
?>*/