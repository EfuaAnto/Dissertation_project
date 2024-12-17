<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


include 'connectionScript.php';
//var_dump($_POST);
//echo '<hr>';
//var_dump($_SERVER);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["error" => "User not logged in."]);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $weight = $_POST['weight'];
    $date_time = $_POST['date_time'];
    $user_id = $_SESSION["user_id"];

    // Fetch target_weight
    $sql = "SELECT target_weight FROM user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(["error" => "User target weight not found."]);
        exit();
    }

    $target_weight = $row['target_weight'];

    // Calculate body mass percentage
    $bodyMass_percentage = abs($weight - $target_weight) / $weight * 100;
    $bodyMass_percentage = round($bodyMass_percentage, 2);
    

    // Insert into weight_recorded table
    $sql2 = "INSERT INTO weight_recorded (user_id, weight, date_time, bodyMass_percentage) VALUES (?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("idsd", $user_id, $weight, $date_time, $bodyMass_percentage);

    if ($stmt2->execute()) {
        echo json_encode(["message" => "weight added"]);
        header("Location: ../P_logWeight.php");
    } else {
        echo json_encode(["error" => "Couldn't execute query: " . $stmt->error]);
    }

  $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}

$conn->close();


/*
<?php
session_start();
include 'connectionScript.php';
//var_dump($_POST);
//echo '<hr>';
//var_dump($_SERVER);

error_reporting(E_ALL);
ini_set('display_errors', 1);




if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  

// Retrieve and sanitize input
$weight = $_POST['weight'];
$date_time = isset($_POST['date_time']) && !empty($_POST['date_time']) ? $_POST['date_time'] : null;
$user_id = $_SESSION["user_id"];

// Get target weight from `user_tbl`
$sql = "SELECT target_weight FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();



$target_weight = $row['target_weight'];

// Calculate body mass percentage
$bodyMass_percentage = (($weight - $target_weight) / $weight) * 100;
$bodyMass_percentage = round($bodyMass_percentage, 2);

if ($date_time) {
    // Insert with user-provided date_time
    $sql2 = 'INSERT INTO weight_recorded (user_id, weight, date_time, bodyMass_percentage) VALUES (?, ?, ?, ?)';
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("idid", $user_id, $weight, $date_time, $bodyMass_percentage);
} else {
    // Insert without date_time (uses CURRENT_TIMESTAMP)
    $sql2 = 'INSERT INTO weight_recorded (user_id, weight, bodyMass_percentage) VALUES (?, ?, ?)';
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("idi", $user_id, $weight, $bodyMass_percentage);
}


if ($stmt2->execute()) {
   
    header("Location: ../P_logWeight.php");
} else {
    echo json_encode(["error" => "Couldn't execute query: " . $stmt->error]);
}

$stmt->close();
$stmt2->close();

$conn->close();
}
<?php
session_start();
include 'connectionScript.php';
//var_dump($_POST);
//echo '<hr>';
//var_dump($_SERVER);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["error" => "User not logged in."]);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}


$weight = $_POST['weight'];
$date_time = $_POST['date_time'];
$user_id = $_SESSION["user_id"];

$sql = "SELECT target_weight FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$target_weight = $row['target_weight'] ;   

    $bodyMass_percentage = (($weight - $target_weight) / $weight) * 100;
    $bodyMass_percentage = round($bodyMass_percentage, 2);
    echo json_encode(["bodyMass_percentage" => $bodyMass_percentage]);


$sql2 = 'INSERT INTO weight_recorded (user_id, weight, date_time, bodyMass_percentage) VALUES (?, ?, ?, ?)';
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("idid", $user_id, $weight, $date_time, $bodyMass_percentage);

    if ($stmt2->execute()) {
        echo json_encode(["message" => "weight added"]);
        header("Location: ../P_logWeight.php");
    } else {
        echo json_encode(["error" => "Couldn't execute query: " . $stmt->error]);
    }

  $stmt->close();
  $stmt2->close();

$conn->close();

 */
?>