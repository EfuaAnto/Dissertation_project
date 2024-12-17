<?php
session_start();
   
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $username = $_POST['username'];
    $password = $_POST['password'];

        if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Username and password cannot be empty."]);
        exit();
    }


 $sql = "SELECT username, password FROM admin WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $password_hash = $row['password'];
        
    if (password_verify($password, $password_hash)) { 
       
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No account found with that username.']);
}

$stmt->close();
$conn->close();
}

?>
