<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["password"];

    // Update the password in the database
    $sql = "UPDATE employees SET Employee_PassKey = ? WHERE Employee_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        echo "Password reset successfully.";
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating password: " . htmlspecialchars($stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Set New Password</title>
</head>
<body style="font-family: 'Rubik', sans-serif;">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<center>
<div>
    <img src="logo.png" alt="Austin's Logo" >
    <p style=" padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
    <div class="card text-bg-light mb-6" style="justify-content: center; width: 25rem; padding:30px">
        <h5>Set Password</h5></br>
        <form id="setPassForm" action="setpass.php" method="POST">
            <!-- Email input -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input name="email" type="text" class="form-control" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($_GET['email']); ?>" readonly>
            </div>

            <!-- Password input -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-lock"></i>
                </span>
                <input name="password" type="password" class="form-control" placeholder="New Password" aria-label="Password" aria-describedby="basic-addon1" id="password" required />
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block">Continue</button>
        </form>
    </div>
</div>
</center>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const setPassForm = document.getElementById('setPassForm');

    setPassForm.addEventListener('submit', function(event) {
        const storedOtp = localStorage.getItem('otp');
        const otpExpiresAt = localStorage.getItem('otpExpiresAt');

        if (new Date().getTime() > otpExpiresAt) {
            alert('Invalid or expired reset token.');
            event.preventDefault();
            window.location.href = 'forgotpass.php';
        }
    });
});
</script>
</body>
</html>