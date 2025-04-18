<?php
include '../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS);
    $mobileNumber = filter_input(INPUT_POST, 'mobile_number', FILTER_SANITIZE_NUMBER_INT);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $status = 'Active';

    if (empty($firstName) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        try {
            // Check if the user already exists
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE Employee_Email = :email");
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                echo "<script>alert('User already exists');</script>";
            } else {
                $employeeId = mt_rand(100000000, 999999999);
                $stmt = $conn->prepare("INSERT INTO employees (Employee_ID, Employee_Name, Employee_Email, Employee_PassKey, Employee_PhoneNumber, Employee_Role, Employee_Status) VALUES (:employeeId, :fullName, :email, :password, :mobileNumber, :role, :status)");
                $fullName = $firstName . ' ' . $lastName;
                $stmt->bindParam(':employeeId', $employeeId);
                $stmt->bindParam(':fullName', $fullName);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':mobileNumber', $mobileNumber);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':status', $status);

                if ($stmt->execute()) {
                    ob_start();
                    header("Location: /Austin-sIMS-POS/Admin/adminEmployees.php");
                    ob_end_flush();
                    exit();
                } else {
                    echo "Execute failed: (" . $stmt->errorInfo()[2] . ")";
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" href="styles/adminAddUser.css">
    <link rel="stylesheet" href="styles/adminNav.css">
</head>

<body>
    <?php include 'adminNavBar.php'; ?>
    <div id="adminContent">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="page-title">Add New User</h1>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" id="accountDropdownBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Administrator
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
        <div class="card p-4">
            <h2 class="text-center h4 fw-bold">Please fill in information</h2>
            <p class="text-center text-muted">Enter details to get going.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Full Name</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="firstName">First Name*</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="first_name" class="form-control" id="firstName" placeholder="John">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="lastName">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="last_name" class="form-control" id="lastName" placeholder="Doe">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Contact Email</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email*</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" id="email" placeholder="johndoe@gmail.com">
                            <div class="invalid-feedback">Please enter a valid email</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mobileNumber">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" name="mobile_number" class="form-control" id="mobileNumber" placeholder="(+63) 912 3456 789">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Password</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Enter password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" id="password">
                            <span class="input-group-text">
                                <i class="fas fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="rePassword">Re-enter password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="confirm_password" class="form-control" id="rePassword">
                            <span class="input-group-text">
                                <i class="fas fa-eye" id="toggleRePassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Position</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="role">Role</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-users"></i>
                            </span>
                            <select name="role" class="form-select" id="role">
                                <option value="" selected disabled>Select Role</option>
                                <option value="Employee">Employee</option>
                                <option value="POS Staff Management">POS Staff Management</option>
                                <option value="Inventory Staff Management">Inventory Staff Management</option>
                                <option value="Administrator">Administrator</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="status" value="Active">
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-custom px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'cdnScripts.php'; ?>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        const toggleRePassword = document.querySelector('#toggleRePassword');
        const rePassword = document.querySelector('#rePassword');
        toggleRePassword.addEventListener('click', function(e) {
            const type = rePassword.getAttribute('type') === 'password' ? 'text' : 'password';
            rePassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>