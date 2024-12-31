<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require 'connectionScript.php';

$user_id = $_SESSION['user_id'];


$query = "SELECT target_weight FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$target_weight = $user['target_weight'];

$query = "SELECT weight FROM weight_recorded WHERE user_id = ? ORDER BY date_time ASC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$earliest_log = $result->fetch_assoc();

$starting_weight = $earliest_log['weight'];

$query = "SELECT weight FROM weight_recorded WHERE user_id = ? ORDER BY date_time DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$latest_log = $result->fetch_assoc();

$current_weight = $latest_log['weight'];

if ($target_weight < $starting_weight) {
  
    $progress = (($starting_weight - $current_weight) / ($starting_weight - $target_weight)) * 100;
} else {
   
    $progress = (($current_weight - $starting_weight) / ($target_weight - $starting_weight)) * 100;
}


$progress = max(0, min($progress, 100));

echo json_encode([
    'current_weight' => $current_weight,
    'target_weight' => $target_weight,
    'progress' => $progress
]);
?>

