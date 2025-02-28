<?php 
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields";
    } else {
        $user = null;

        try {
            $stmt = $conn->prepare("SELECT Employee_PassKey, Employee_Role FROM employees WHERE Employee_Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($stored_password, $role);
            $stmt->fetch();
            $stmt->close();

            if ($password === $stored_password) {
                $role = strtolower($role);
                // Password is correct
                if ($role === 'admin') {
                    header("Location: /Austin-sIMS-POS/Admin/adminDashboard.php");
                } else {
                    header("Location: /Austin-sIMS-POS/IMS-POS/Menu.php");
                }   

                exit();
            } else {
                // Invalid password
                echo "Invalid email or password";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Please enter a valid email and password";
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <title>Login Page</title>
</head>
<body style="font-family: 'Rubik', sans-serif;">
<center>
  <div>
    <img src="logo.png" alt="Austin's Logo">
    <p style=" padding-bottom:20px; color:#6a4413; font-size:25px">Inventory Management - Point of Sale System</p>
      <div class="card text-bg-light mb-6" style="justify-content: center; width: 25rem;">
        <div style="padding: 40px;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <!-- Email input -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" name="email" class="form-control" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1">
                </div>

                <!-- Password input -->
                <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-lock"></i>
                        </span>
                    <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" id="password" required />
                    <a href="/Austin-sIMS-POS/Login/forgotpass.php" class="d-block text-end mt-2" style="padding-left: 180px">Forgot password?</a>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block">Continue</button>
            </form>
        </div>
      </div>
  </div>
  </center>
</body>
</html>