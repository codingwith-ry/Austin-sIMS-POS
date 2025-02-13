<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <title>Forgot Password</title>
</head>
<body style="font-family: 'Rubik', sans-serif;">
<center>
<div>
<img src="logo.png" alt="Austin's Logo" >
<p style=" padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
    <div class="card text-bg-light mb-6" style="justify-content: center; width: 25rem; padding:30px">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- Email input -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input name="email" type="text" class="form-control" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1">
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
        </form>
    </div>
</div>
</center>
</body>
</html>

<?php
include("database.php");
require __DIR__ . "/mailer.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $reset_token_hash = hash("sha256", $otp);
    $reset_token_expires_at = date("Y-m-d H:i:s", time() + 60 * 30);

    $sql = "UPDATE employees SET reset_token_hash = ?, reset_token_expires_at = ? WHERE Employee_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $reset_token_hash, $reset_token_expires_at, $email);

    if ($stmt->execute()) {
        $mail = require __DIR__ . "/mailer.php";

        try {
            $mail->addAddress($email);
            $mail->Subject = "Your OTP Code";
            $mail->Body = "Your OTP code is: $otp";

            if ($mail->send()) {
                echo "OTP sent successfully.";
                header("Location: otp.php?email=" . urlencode($email));
                exit();
            } else {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Execute failed: " . htmlspecialchars($stmt->error);
    }
}
?>
