<?php
//start session connects to database script and set  sets the Content-Type header to application/json
session_start();
header('Content-Type: application/json');


require 'db_connection.php';
//check for database connection if not error is sent to user
//sets the connection to use the utf8mb4 character encoding for all data exchanged between PHP and the MySQL database.
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}
//making sure script processes post requests only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     //extracting input data sent by post request and asigning it variables
    $input = file_get_contents("php://input");
$data = json_decode($input, true);

    $programme_summary =  $data['programme_summary'] ?? null;
    $homepage_id = $data['homepage_id'] ?? null;
      
 // prepared sql query for dynamic update for most fields except lesson_id 
    $sql = "UPDATE homepage_content SET programme_summary = ? WHERE homepage_id = ?" ;
     //SQL statement preparing the statement 
    $stmt = $conn->prepare($sql);
//error debugging  check if preparations worked or not 
    if ($stmt === false) {
        echo json_encode(["error" => "Database query preparation failed: " . $conn->error]);
        exit;
    }

  //binding parameters to the sql statement
     $stmt->bind_param("si", $programme_summary, $homepage_id);
//executing the statement with different json alert depending if succesful or not
    

    if ($stmt->execute()) {
     
        echo json_encode(["message" => "programme updated successfully."]);
    } else {
        echo json_encode(["error" => "Could not update lesson: " . $stmt->error]);
    }
//closing the statement
    $stmt->close();
} else {//if the request method is not Post it will trow a 405 status and network message  
    
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}
//closing the connection
$conn->close();
?>
