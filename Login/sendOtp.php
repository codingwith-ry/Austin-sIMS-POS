<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$otp = $data['otp'];

$mail = require __DIR__ . "/mailer.php";

try {
    $mail->addAddress($email);
    $mail->Subject = "Your OTP Code";
    $mail->Body = "Your OTP code is: $otp";

    if ($mail->send()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
}
?>