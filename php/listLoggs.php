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
                'user_id' => $row['user_id'] ?? null, // Add null check
                'weight_id' => $row['weight_id'] ?? null // Add null check
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

/*<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

require 'connectionScript.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "User not authenticated."]);
        exit;
    }

    $filterType = $data['filterType'] ?? null;
    $selectedValue = $data['selectedValue'] ?? null;
    $sortOrder = $data['sortOrder'] ?? 'DESC';

    $sql = "SELECT DATE(date_time) AS full_date, YEAR(date_time) AS year, 
                    MONTH(date_time) AS month, DAY(date_time) AS day, 
                    weight, bodyMass_percentage 
             FROM weight_recorded 
             WHERE user_id = ?";
    
    if ($filterType === 'year') {
        $sql .= " AND YEAR(date_time) = ?";
    } elseif ($filterType === 'month') {
        $sql .= " AND MONTH(date_time) = ?";
    }
    $sql .= " ORDER BY date_time " . ($sortOrder === 'oldest' ? 'ASC' : 'DESC');

    $stmt = $conn->prepare($sql);

    if ($filterType === 'year' || $filterType === 'month') {
        $stmt->bind_param("is", $user_id, $selectedValue);
    } else {
        $stmt->bind_param("i", $user_id);
    }

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
                'bodyMass_percentage' => $row['bodyMass_percentage']
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


    $stmt = $conn->prepare($sql);  
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && mysqli_num_rows($result) > 0) {
        $Logs = [];
        while ($row = $result->fetch_assoc()) {
            $Logs[] = $row; 
        } 
        echo json_encode($Logs); 
    } else {
        echo json_encode(["message" => "No weight recorded."]); 
    }

    $stmt->close();
} else {
    
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]); 
}

$conn->close();

*/
?>