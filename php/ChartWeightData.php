<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



header('Content-Type: application/json');
require 'connectionScript.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data =[];
    $user_id = $_SESSION['user_id'] ?? null;
    $selected_year = isset($data['year']) ? $data['year'] : '0000';
    $selected_month = isset($data['month']) ? $data['month'] : '00';

   
    $sql = "SELECT Weight ,DATE(date_time) AS full_date, YEAR(date_time) AS year, 
    MONTH(date_time) AS month, DAY(date_time) AS day,WEEK(date_time) AS week,
    weight , bodyMass_percentage 
    FROM weight_recorded 
    WHERE user_id =? ORDER by date_time ASC";

     $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
     $stmt->execute();
     $result = $stmt->get_result();
 
     
     if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'full_date' => $row['full_date'],
                'year' => $row['year'],
               'month' => $row['month'],
                'day' => $row['day'],
                'week' => $row['week'],
                'weight' => $row['weight'],
                'bodyMass_percentage' => $row['bodyMass_percentage']
                ];
            }
        } else {
            $data = ['error' => 'No data found'];
        }
        
    
 
     $stmt->close();
     $conn->close();
 
     echo json_encode($data);
 

}
 ?>