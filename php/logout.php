<?php

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
session_unset();
session_destroy(); 
 echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
    exit();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
} 

?>
