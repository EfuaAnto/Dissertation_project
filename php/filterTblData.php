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
    $selected_year = isset($data['year']) ? $data['year'] : '0000';
    $selected_month = isset($data['month']) ? $data['month'] : '00';

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "User not authenticated."]);
        exit;
    }

    error_log("User ID: $user_id, Year: $selected_year, Month: $selected_month");
    $sql = "SELECT YEAR(date_time) AS year, 
    MONTH(date_time) AS month, 
    DATE(date_time) AS full_date, 
    DAY(date_time) AS day, 
    weight, 
    bodyMass_percentage 
FROM weight_recorded 
WHERE user_id = ? 
AND ((YEAR(date_time) = ?) OR (? = '0000')) 
AND ((MONTH(date_time) = ?) OR (? = '00'))
ORDER BY date_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $user_id, $selected_year, $selected_year ,$selected_month, $selected_month);
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
                'bodyMass_percentage' => $row['bodyMass_percentage' ],
            
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