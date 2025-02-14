<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" href="adminAddUser.css">
</head>
<body>
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>
    <div class="container mt-5" id="main-content">
        <div class="header d-flex justify-content-between align-items-center mb-4">
            <h1>Add New User</h1>
            <div class="user-info d-flex align-items-center">
                <img src="https://placehold.co/40x40" alt="User avatar" class="rounded-circle" width="40" height="40">
                <span class="ms-2 font-weight-bold">Administrator</span>
                <i class="fas fa-chevron-down ms-2"></i>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="title text-center">Please fill in information</h2>
                <p class="subtitle text-center text-muted">Enter details to get going.</p>
                <form action="processAddUser.php" method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" id="fullName" value="John Doe" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">Contact email</label>
                            <input type="email" class="form-control" id="email" value="johndoe@gmail.com" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" required>
                            <div class="invalid-feedback">Please enter a password.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role">Position</label>
                            <input type="text" class="form-control" id="role" value="Employee Staff" disabled>
                        </div>
                    </div>
                    <div class="button-container text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End main content -->
</body>
</html>
