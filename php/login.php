<?php
session_start();
   
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connectionScript.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = $_POST['email'];
    $password = $_POST['password'];


        if (empty($email) || empty($password)) {
             setcookie("status", "email and password cannot be empty.");
            header("Location: http://localhost/Dissertation_project/Login_&_Registration.html");
            die();
        }

  
    $sql = "SELECT email, password,user_id FROM user_tbl WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
              $password_hash = $row['password'];
           
    if (password_verify($password, $password_hash)) {
        $_SESSION["loggedin"] = true;
        $_SESSION["email"] = $row["email"];
        $_SESSION["user_id"] = $row["user_id"];

    /*    header("Location: http://localhost/Dissertation_project/P_logWeight.php");
        die();
        }
    } else {
        setcookie("status", "invalid credentials");
        header("Location: http://localhost/Dissertation_project/P_Login_&_Registration.php");
        die();
    }
*/
    echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No account found with that email.']);
}
$stmt->close();
$conn->close();
}

?>
