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
}

$sql = "SELECT * FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
 
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $height = floatval($data['height']);
    $height_m = $height / 100;

    $sql2 = "SELECT YEARWEEK(date_time, 1) AS week, AVG(weight) AS avg_weight 
            FROM weight_recorded
            WHERE user_id = ? 
            GROUP BY week ORDER BY week ASC";
    $weeklyBmi = [];      
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result = $stmt2->get_result();


while ($row = $result->fetch_assoc()) {
    $avg_weight = floatval($row['avg_weight']);
    $bmi = $avg_weight / ($height_m * $height_m);
    $weeklyBmi[$row['week']] = $bmi;
}
//this line of code will get the last 8 weeks of the array
$weeklyBmi = array_slice($weeklyBmi, -8, 8, true);

//this line of code will send the result or error to the user 
    $response = [
        "status" => "success",
        "message" => "BMI calculated ",
        
        "data" => $bmi,
        "weeklyBmi" => $weeklyBmi,
  
    ];

}else{
        $response = [
            "status" => "error",
            "message" => " BMI calculation failed"
        ];
    }
    
    echo json_encode($response);