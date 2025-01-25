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
    <title>OTP Reset Password</title>
</head>
<center>
<body style="font-family: 'Rubik', sans-serif;">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <div>
      <img src="logo.png" alt="Austin's Logo">
      <p style=" padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
      </div>
      <div class="card text-bg-light mb-6" style="width: 25rem; padding:30px">
        <h5 class="card-title text-center mb-4">Enter OTP</h5>
        <p class="text-center">We sent a code to <strong>example@gmail.com</strong></p></br>
        <form action="/Austin-sIMS-POS/Login/otp.php" method="POST">
          <!-- OTP input -->
          <div class="d-flex justify-content-between mb-4">
            <input type="text" class="form-control otp-input" maxlength="1" />
            <input type="text" class="form-control otp-input" maxlength="1" />
            <input type="text" class="form-control otp-input" maxlength="1" />
            <input type="text" class="form-control otp-input" maxlength="1" />
            <input type="text" class="form-control otp-input" maxlength="1" />
            <input type="text" class="form-control otp-input" maxlength="1" />
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
        </form>
      </div>
    </div>
  </div>
</body>
</center>
</html>
