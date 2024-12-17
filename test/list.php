<?php
//start session connects to database script
session_start();
header('Content-Type: application/json');

require 'db_connection.php';
//check for database connection if not error is sent to user
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error])); 
}

//making sure script processes post requests only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // prepared sql query for retrieving data
    $sql = "SELECT * FROM  Lesson_Summaries";
        //SQL statement preparint the statement
    $stmt = $conn->prepare($sql);
        //if SQL query fails get error message
    if ($stmt === false) {
        die(json_encode(["error" => "Database query failed: " . $conn->error])); 
    }
        //executing the statement 
        
    $stmt->execute();
     //get results from executed query
    $result = $stmt->get_result();
    //check if result has rows if there are no rows error message 

    if ($result && mysqli_num_rows($result) > 0) {
        $summaries = [];
                //initializing question array to store data from query
        
        while ($row = $result->fetch_assoc()) {
            $summaries[] = $row; 
        }  //function to fetch each row as an associative array and add it to the question array
      
//send the data as json input back out to the page 
        echo json_encode($summaries); 
    } else {
        echo json_encode(["message" => "No lessons found."]); 
    }
//closing the statement
    
    $stmt->close();
} else {
    
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]); 
}
//closing the connection 
$conn->close();
?>
