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
<body class="d-flex justify-content-center align-items-center vh-100" style="font-family: 'Rubik', sans-serif; background-color: #cdc7b0;">
<center>
<div>
<img src="logo.png" alt="Austin's Logo" class="mb-3" style="width: 30%; height: auto;">
<p style=" padding-bottom:20px; color:#6a4413; font-size: 20px">Inventory Management - Point of Sale System</p>
    <div class="card text-bg-light mb-6" style="justify-content: center; width: 25rem; padding:30px">
        <!-- Forgot Password Header -->
        <h3 class="text-center mb-4" style="color: #6a4413;">Forgot Password</h3>
        <p class="text-center mb-4">Enter your email to receive a link to reset your password.</p>
        
        <form id="forgotPassForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- Email input -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input name="email" type="email" class="form-control" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
            <div class="text-center">
            <a href="/Austin-sIMS-POS/Login/index.php" style="color: #6a4413;">Back to Login</a>
        </div>
        </form>
    </div>
</div>
</center>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forgotPassForm = document.getElementById('forgotPassForm');

    forgotPassForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const email = forgotPassForm.email.value;
        const otp = Math.floor(100000 + Math.random() * 900000).toString();
        const otpExpiresAt = new Date().getTime() + 30 * 60 * 1000; // 30 minutes from now

        localStorage.setItem('otp', otp);
        localStorage.setItem('otpExpiresAt', otpExpiresAt);

        console.log('Email:', email); 
        console.log('OTP:', otp); 
        console.log('OTP Expires At:', otpExpiresAt); 

        fetch('sendOtp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, otp: otp })
        }).then(response => {
            console.log('Fetch response status:', response.status); 
            return response.json();
        }).then(data => {
            console.log('Response:', data); 
            if (data.success) {
                alert('OTP sent successfully.');
                window.location.href = `otp.php?email=${encodeURIComponent(email)}`;
            } else {
                alert('Error sending OTP: ' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the OTP.');
        });
    });
});
</script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $otp = rand(100000, 999999); 

    $mail = require __DIR__ . "/mailer.php";

    try {
        $mail->addAddress($email);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your OTP code is: $otp";

        if ($mail->send()) {
            echo "OTP sent successfully.";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>