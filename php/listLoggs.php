<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require 'connectionScript.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

   
    $user_id = $_SESSION['user_id'];

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "User not authenticated."]);
        exit;
    }

  
    $sql = "SELECT 
                DATE(date_time) AS full_date, 
                YEAR(date_time) AS year, 
                MONTH(date_time) AS month, 
                DAY(date_time) AS day, 
                weight, 
                bodyMass_percentage, 
                user_id, 
                weight_id 
            FROM weight_recorded 
            WHERE user_id = ? 
            ORDER BY date_time DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'full_date' => $row['full_date'],
                'year' => $row['year'],
                'month' => $row['month'],
                'day' => $row['day'],
                'weight' => $row['weight'],
                'bodyMass_percentage' => $row['bodyMass_percentage'],
                'user_id' => $row['user_id'] ?? null, 
                'weight_id' => $row['weight_id'] ?? null 
            ];
        }
    }

    $stmt->close();
    $conn->close();

    
    echo json_encode($data);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}

?>