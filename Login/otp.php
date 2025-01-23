<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>OTP Reset Password</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<form>
  <!-- OTP input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <label class="form-label" for="form2Example1">Enter OTP</label>
    <input type="text" id="form2Example1" class="form-control" />
  </div>

  <!-- New password input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <label class="form-label" for="form2Example2">New Password</label>
    <input type="password" id="form2Example2" class="form-control" />
  </div>

  <!-- Submit button -->
  <button href= "/Austin'sIMS-POS/Login/otp.php" type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
</form>

</body>
</html>
