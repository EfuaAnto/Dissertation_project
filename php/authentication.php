<?php

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo json_encode(['authenticated' => true,'email' => $_SESSION['email'],'user_id' => $_SESSION['user_id']
    ]);
} else {
    echo json_encode(['authenticated' => false]);
}
?>
