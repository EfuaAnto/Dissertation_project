<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');


require 'connectionScript.php';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    echo json_encode(["success" => false, "error" => "User ID is required and cannot be null."]);
    exit;
}
//echo json_encode(["success" => true, "user_id" => $user_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$input = file_get_contents("php://input");
$data = json_decode($input, true);
    $inactivity_reminder =  $data['inactivity_reminder'] ;
    $name = $data['name'] ;
    $surname =  $data['surname'] ;
    $email = $data['email'] ;
    $height =  $data['height'] ;
    $Age = $data['Age'] ;
    $target_weight =  $data['target_weight'] ;
   // $weight = $data['weight'] ;
    $health_Condition =  $data['health_Condition'] ;
  //  $weight_fluctuation = $data['weight_fluctuation'] ;
 
      
    $sql = "UPDATE user_tbl SET  name = ? , surname = ? , email = ? , height = ? , Age = ? , target_weight = ?, health_Condition = ?  WHERE user_id = ?" ;
    
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdidsi", $name, $surname, $email, $height, $Age, $target_weight, $health_Condition ,$user_id );
        $stmt->execute();
        $result = $stmt->get_result();
    
       
          /* 
            $sql2 = "UPDATE  weight_recorded SET weight = ? WHERE user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("di", $weight, $user_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
    */
           // $sql3 = "UPDATE user_preferences SET inactivity_reminder = ? , weight_fluctuation = ? WHERE user_id = ?" ;
            $sql3 = "UPDATE user_preferences SET inactivity_reminder = ?  WHERE user_id = ?" ;
          
            $stmt3 = $conn->prepare($sql3);
           // $stmt3->bind_param("iii", $inactivity_reminder, $weight_fluctuation, $user_id);
            $stmt3->bind_param("ii", $inactivity_reminder, $user_id);
          
           $stmt3->execute();
            $result3 = $stmt3->get_result();
    
           // if ($stmt->execute() && $stmt2->execute() && $stmt3->execute()) {
            if ($stmt->execute() && $stmt3->execute()) {
                echo json_encode(["message" => "Updated successfully."]);
            } else {
               
                $error = "";
                if ($stmt->error) {
                    $error .= "Error in stmt: " . $stmt->error . "\n";
                }
                /*if ($stmt2->error) {
                    $error .= "Error in stmt2: " . $stmt2->error . "\n";
                }*/
                if ($stmt3->error) {
                    $error .= "Error in stmt3: " . $stmt3->error . "\n";
                }
            
                echo json_encode(["error" => "Could not update: " . $error]);
            }
            
        
        $stmt->close();
        //$stmt2->close();
       $stmt3->close();
    
    $conn->close();
      

} else {
    echo json_encode(["success" => false, "error" => "User ID not provided"]);
}
    ?>