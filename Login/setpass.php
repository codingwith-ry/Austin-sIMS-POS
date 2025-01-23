<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Set New Password</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<div class="setpass-container">
    <form action="/Austin-sIMS-POS/Login/otp.php" method="POST">
        <!-- New password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example1">New Password</label>
            <input type="password" id="form2Example1" name="new_password" class="form-control" required />
        </div>

        <!-- Confirm password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example2">Confirm Password</label>
            <input type="password" id="form2Example2" name="confirm_password" class="form-control" required />
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Set Password</button>
    </form>
</div>

</body>
</html>