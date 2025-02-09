<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_GET["email"];
    $otp = implode("", $_POST["otp"]); // Combine the OTP inputs
    $reset_token_hash = hash("sha256", $otp);

    $sql = "SELECT reset_token_hash, reset_token_expires_at FROM employees WHERE Employee_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_reset_token_hash, $reset_token_expires_at);
    $stmt->fetch();
    $stmt->close();

    if ($reset_token_hash === $stored_reset_token_hash && strtotime($reset_token_expires_at) > time()) {
        echo "OTP verified successfully.";
        header("Location: setpass.php?email=" . urlencode($email) . "&reset_token_hash=" . urlencode($reset_token_hash));
        exit();
    } else {
        echo "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>OTP Reset Password</title>
</head>
<body style="font-family: 'Rubik', sans-serif;">
<center>
  <div>
      <img src="logo.png" alt="Austin's Logo">
      <p style="padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
  </div>
  <div class="card text-bg-light mb-6" style="width: 25rem; padding:30px">
    <h5 class="card-title text-center mb-4">Enter OTP</h5>
    <p class="text-center">We sent a code to <strong><?php echo htmlspecialchars($_GET["email"]); ?></strong></p></br>
    <form action="otp.php?email=<?php echo urlencode($_GET["email"]); ?>" method="POST">
      <div class="d-flex justify-content-between mb-4">
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" />
      </div>
      <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
    </form>
  </div>
</center>
</body>
</html>