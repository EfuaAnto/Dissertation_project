<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

$mail = new PHPMailer(true);

try {
    
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
   
  
    $mail->setFrom('DissertationProjectEmailAlerts@gmail.com', 'admin');
    $mail->addAddress('hilldifferent@gmail.com'); 
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h1>Hello!</h1><p>This is a test email sent using PHPMailer.</p>';

  
    $mail->send();
    echo '✅ Email has been sent successfully!';
} catch (Exception $e) {
    echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
}