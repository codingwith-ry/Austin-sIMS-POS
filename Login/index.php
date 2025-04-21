<?php
session_start();
include("database.php");

$errorMessage = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($email) || empty($password)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT Employee_PassKey, Employee_Role, Employee_ID FROM employees WHERE Employee_Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password === $user['Employee_PassKey']) {
                $role = strtolower($user['Employee_Role']);
                // Password is correct
                $_SESSION['userRole'] = $role;
                $_SESSION['email'] = $email;
                $_SESSION['employeeID'] = $user['Employee_ID'];

                if ($role === 'administrator') {
                    header("Location: /Austin-sIMS-POS/Admin/adminDashboard.php");
                } else if ($role === 'pos staff management') {
                    header("Location: /Austin-sIMS-POS/IMS-POS/Menu.php");
                } else if ($role === 'inventory staff management') {
                    header("Location: /Austin-sIMS-POS/IMS-POS/stockPage.php");
                } else {
                    $errorMessage = "Invalid email or password.";
                }
                exit();
            } else {
                // Invalid password
                $errorMessage = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $errorMessage = "An error occurred. Please try again.";
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

<body class="d-flex justify-content-center align-items-center vh-100" style="font-family: 'Rubik', sans-serif; background-color: #cdc7b0;">
    <center>
        <div>
            <img src="logo.png" alt="Austin's Logo" class="mb-4" style="width: 30%; height: auto;">
            <p class="mb-4" style="color: #6a4413; font-size: 20px; margin: auto;">Inventory Management - Point of Sale System</p>
            <div class="card text-bg-light mx-auto" style="width: 25rem;">
                <div class="card-body p-4">
                    <!-- Login Header -->
                    <h3 class="text-center mb-3" style="color: #6a4413;">Login</h3>
                    <p class="text-center mb-4">Please enter your email and password.</p>

                    <!-- Display error message -->
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <!-- Email input -->
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input type="text" name="email" class="form-control <?php echo !empty($errorMessage) ? 'is-invalid' : ''; ?>" placeholder="Email address" aria-label="Email address" aria-describedby="basic-addon1" required>
                        </div>

                        <!-- Password input with toggle -->
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control <?php echo !empty($errorMessage) ? 'is-invalid' : ''; ?>" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" id="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="color: #6a4413; border-color: #6a4413;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <a href="/Austin-sIMS-POS/Login/forgotpass.php" class="d-block text-end mb-3" style="color: #6a4413;">Forgot password?</a>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary w-100">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </center>

    <script src="/Austin-sIMS-POS/Login/scripts/index.js"></script>
</body>

</html>