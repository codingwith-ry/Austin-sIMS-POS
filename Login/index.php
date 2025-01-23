<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login Page</title>
</head>
<body>
    <div class="login-container">
        <img src="logo.png" alt="Austin's Logo">
        <p>Inventory Management - Point of Sale System</p>

        <form action="login.php" method="POST">
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required />
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required />
            </div>

            <!-- Forgot Password -->
            <div class="mb-4">
                <a href="/Austin-sIMS-POS/Login/forgotpass.php">Forgot password?</a>
            </div>
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
        </form>
    </div>
</body>
</html>