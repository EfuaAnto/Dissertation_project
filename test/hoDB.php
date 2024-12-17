<?php
//start session connects to database script 
session_start();
header('Content-Type: application/json');


require 'db_connection.php';

$conn->set_charset("utf8mb4");
//check for database connection if not error is sent to user
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

//making sure script processes post requests only 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Retrieving json raw input data from the request body
//extracting input data sent by post request and asigning it variables
    $input = file_get_contents("php://input");
    // Decoding the JSON input into a PHP associative array
    $data = json_decode($input, true);

   
    //error reporting
    //error_log("Received data: " . print_r($data, true));

//extracting input data from JSON request and asigning it variables
    $homepage_id = $data['homepage_id'];
        // prepared sql query for dynamic input 
    $sql = "SELECT programme_summary FROM homepage_content WHERE homepage_id = ?";
   //SQL statement preparint the statement 
    $stmt = $conn->prepare($sql);

     //binding parameters to the sql statement
    $stmt->bind_param("i", $homepage_id);
       //executing the statement 
    $stmt->execute();
   //get results from executed query
    $result = $stmt->get_result();
    //check if result has rows if there are no rows error message 
    if ($result->num_rows > 0) {
  //creating a variable where to store result
        $summ = $result->fetch_assoc();
     //array is then converted to json and sent back to page as  response 
        echo json_encode($summ);
    } else {
        echo json_encode(["error" => "programme summary not found."]);
    }

        //closing the statemen
    $stmt->close();
} else {
     //if the request method is not Post it will trow a 405 status and network message  
    http_response_code(405);
   echo json_encode(["error" => "Method not allowed."]);
}

//closing the connection 
$conn->close();

?>
