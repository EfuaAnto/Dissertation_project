<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'connectionScript.php';

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $username = $_POST['username'];
    $password = $_POST['password'];
    $name= $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $height = $_POST['height'];
    $target_weight = $_POST['target_weight'];
    $health_Condition = $_POST['health_Condition'];
    $weight = $_POST['weight'];
    $age = $_POST['age'];
    $target_weight = $_POST['target_weight']; //isset($_POST['target_weight'])// && !empty($_POST['target_weight'])
   
    if ($target_weight > 0){ 
        $bodyMass_percentage = (($weight - $target_weight) / $weight) * 100;
        $bodyMass_percentage = round($bodyMass_percentage, 2);
    } else {
        $bodyMass_percentage = 10; 
    }
        if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Username and password cannot be empty."]);
        exit();
    }
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

 $sql = "INSERT INTO user_tbl ( username, password,name,surname,email,height,target_weight,health_Condition,age)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssddsi",  $username, $password_hash, $name, $surname, $email, $height, $target_weight, $health_Condition, $age);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $user_id = $stmt->insert_id;
    }

    $sql2 = "INSERT INTO weight_recorded (user_id,weight,bodyMass_percentage) VALUES (?,? ,?)";
    
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("idd",  $user_id, $weight, $bodyMass_percentage);
    $stmt2->execute();
    
if ($stmt2->affected_rows > 0) {

    $milestone_alert = 1; 
    $inactivity_reminder = 1; 
    $weight_fluctuation = 0; 
    $sql3 = "INSERT INTO user_preferences (user_id, milestone_alert, inactivity_reminder, weight_fluctuation) VALUES (?,?,?,?)";
    
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("iiid", $user_id, $milestone_alert, $inactivity_reminder, $weight_fluctuation);
    $stmt3->execute();



    if ($stmt3->affected_rows > 0) {
        header("Location: ../P_Login_&_Registration.php");
        exit();  
    } else {
        echo json_encode([ "error inserting data."]);
    }
} else {
    echo json_encode(["There was an error during registration."]);
}
}   
$stmt->close();
$stmt2->close();

$conn->close();
?>
