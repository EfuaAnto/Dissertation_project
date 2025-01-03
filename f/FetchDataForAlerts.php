
<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'connectionScript.php';
require 'vendor/autoload.php';



function InactivityReminderStatus($user_id, $email, $inactivity_days){
    require 'connectionScript.php';   
    $sql = "SELECT MAX(date_time) AS last_entry_date FROM weight_recorded WHERE user_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $latestWeightLogDate = $row['last_entry_date'];
    echo "Latest weight log date: " . $latestWeightLogDate . "<br>";

    if($latestWeightLogDate){
        $today = new DateTime() ;
        $latestWeightLog = new DateTime($latestWeightLogDate);
        echo "Latest weight log date: " . $latestWeightLog->format('Y-m-d') . "<br>";
        $days_inactive = $today->diff($latestWeightLog)->days;

        if($days_inactive >= $inactivity_days){
            $subject = "Inactivity Reminder";
            $message = "You have not recorded your weight for " . $days_inactive . " days.";
            EmailAlertFunction($email, $subject, $message);
           
        }

    } else {
        $subject = "Inactivity Reminder";
        $message = "Hello, you have not entered any weight data yet. Please log your progress.";
        EmailAlertFunction($email, $subject, $message);
    }

} // end of InactivityReminderStatus function

/
function WeightFluctuationStatus($user_id, $email){
    // Check if today is Saturday or Monday
    $today = new DateTime();
    $dayOfWeek = $today->format('N'); // 1 (for Monday) through 7 (for Sunday)

    if ($dayOfWeek == 1 ) {
        // If today is Monday (1)proceed with the status check
    require 'connectionScript.php';
    $sql = "SELECT AVG(weight) AS current_avg_weight_week , date_time 
    FROM weight_recorded 
    WHERE user_id = ? AND date_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_avg_weight_week = $row['current_avg_weight_week'];
echo "Current average weight: " . $current_avg_weight_week . "<br>";
    $sql2 = "SELECT AVG(weight) AS last_avg_weight_week 
    FROM weight_recorded
    WHERE user_id = ? AND date_time BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $last_avg_weight_week = $row2['last_avg_weight_week'];
        echo "Last week's average weight: " . $last_avg_weight_week . "<br>";
      
   


    if ($last_avg_weight_week && $current_avg_weight_week) {

        $weight_difference=  round($current_avg_weight_week - $last_avg_weight_week);
echo "Weight difference: " . $weight_difference . "<br>";

        if ($weight_difference >= 2) {
            $subject = "Weight gain Alert";
            $message = "Youve gained " . $weight_difference . " kg outside of the healthy recommendation weight range."
            . "<html>
            <body>
            <h2>Weight Gain Alert</h2>
            <p>Your weight increased by " . abs($weight_difference) . " kg compared to last week. Please review your plan.</p>
            <p>Thank you,<br>The Team</p>
            </body>
            </html>";
            EmailAlertFunction($email, $subject, $message);
        } elseif ($weight_difference < -2) {
                $subject = "Weight Fluctuation Alert";
                $message = "Your weight has fluctuated by " . $weight_difference. " kg.";
                EmailAlertFunction($email, $subject, $message);

                $subject = "Weight Loss Alert";
                $message = "
                <html>
                <body>
                <h2>Weight Loss Alert</h2>
                <p>Your weight decreased by " . abs($weight_difference) . " kg compared to last week. Please review your plan.</p>
                <p>Thank you,<br>The Team</p>
                </body>
                </html>";
                EmailAlertFunction($email, $subject, $message);
            } 
    }
} else {
   
    $last_avg_weight_week = $current_avg_weight_week;
}
} // end of WeightFluctuationStatus function


function EmailAlertFunction($to, $subject, $message) {
    
    $mail = new PHPMailer(true);
    try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'DissertationProjectEmailAlerts@gmail.com';
    $mail->Password = 'gogz zotw ozcm fuhi';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPOptions = [
    'ssl' => [
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true,
    ],
    ];
    // Email sender and recipient
    $mail->setFrom('DissertationProjectEmailAlerts@gmail.com', 'admin');
    $mail->addAddress($to);

    // Email content
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
    <h2 style='color: #333;'>$subject</h2>
    <p>$message</p>
    <p style='color: #999; font-size: 12px;'>Thank you,<br>Your App Team</p>
    </body>
    </html>";
    $mail->AltBody = strip_tags($message); 

    $mail->send();
    echo "Email sent successfully to $to.<br>";
} catch (Exception $e) {
    echo "Failed to send email to $to. Error: {$mail->ErrorInfo}<br>";
}
} // end of EmailAlertFunction function


    $sql = "SELECT u.user_id, u.email, p.inactivity_reminder
            FROM user_tbl u
            JOIN user_preferences p ON u.user_id = p.user_id";

    $result = $conn->query($sql);

    $preferences = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $preferences = [
                'user_id' => $row['user_id'],
                'email' => $row['email'],
                'inactivity_days' => $row['inactivity_reminder'],
             

            ];
            echo "Target user " . $row['user_id'];
            echo "Target email " . $row['email'];
            InactivityReminderStatus($row['user_id'], $row['email'], $row['inactivity_reminder']);
         
        WeightFluctuationStatus($row['user_id'], $row['email']);
    }
}

$conn->close();
?>