<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Forgot Password</title>
</head>
<body>
<center>
<div>
<img src="logo.png" alt="Austin's Logo" >
<p style=" padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
    <div class="card text-bg-light mb-6" style="justify-content: center; width: 25rem; padding:30px">
        <form action="/Austin-sIMS-POS/Login/otp.php" method="POST">
            <!-- Email input -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input type="text" class="form-control" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1">
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
        </form>
    </div>
</div>
</center>
</body>
</html>