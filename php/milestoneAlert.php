<?php

session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connectionScript.php';


$input = file_get_contents("php://input");
$data = json_decode($input, true);

$user_id = $data['user_id'];
$milestone_alert = $data['milestone_alert'];
$milestone_alert = ($milestone_alert === 'on') ? 1 : (($milestone_alert === 'off') ? 0 : null);

if ($milestone_alert === null) {
    echo json_encode(['error' => 'Invalid milestone_alert provided.']);
    exit;
}

$sql = "UPDATE user_preferences SET milestone_alert =? WHERE user_id =?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('ii', $milestone_alert, $user_id);
    if ($stmt->execute()) {
        $message = $milestone_alert ? 'Milestone alert enabled' : 'Milestone alert disabled';
        echo json_encode(['success' => true, 'message' => $message]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();

?>