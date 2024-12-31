<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



header('Content-Type: application/json');
require 'connectionScript.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data =[];
    $user_id = $_SESSION['user_id'] ;
    
   
    $sql = "SELECT DISTINCT YEAR(date_time) AS year
FROM weight_recorded 
WHERE user_id = ?
ORDER BY YEAR(date_time) DESC";

     $stmt = $conn->prepare($sql);
     $stmt->bind_param("i", $user_id);
     $stmt->execute();
     $result = $stmt->get_result();
 
     $sql2 = "SELECT DISTINCT WEEK(date_time) AS week
     FROM weight_recorded 
     WHERE user_id = ?
     ORDER BY YEAR(date_time) DESC";
     
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bind_param("i", $user_id);
          $stmt2->execute();
          $result2 = $stmt2->get_result();


     if ($result && $result->num_rows > 0 && $result2 && $result2->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row2 = $result2->fetch_assoc();
            $data[] = [
                'year' => $row['year'],
                'week' => $row2['week'],
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