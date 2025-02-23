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
    <form id="otpForm">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const email = new URLSearchParams(window.location.search).get('email');
    const otpForm = document.getElementById('otpForm');

    otpForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const otpInputs = document.querySelectorAll('input[name="otp[]"]');
        let otp = '';
        otpInputs.forEach(input => otp += input.value);

        const storedOtp = localStorage.getItem('otp');
        const otpExpiresAt = localStorage.getItem('otpExpiresAt');

        if (otp === storedOtp && new Date().getTime() < otpExpiresAt) {
            alert('OTP verified successfully.');
            window.location.href = `setpass.php?email=${encodeURIComponent(email)}`;
        } else {
            alert('Invalid or expired OTP.');
        }
    });
});
</script>
</body>
</html>