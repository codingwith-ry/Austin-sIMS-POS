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
                <div class="profile-dropdown d-flex align-items-center admin-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="profile-pic.png" alt="Profile Picture" class="admin-avatar me-2">
                    <span class="fw-semibold">Administrator</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </div>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Admin 1</a></li>
                    <li><a class="dropdown-item" href="#">Admin 2</a></li>
                    <li><a class="dropdown-item" href="#">Admin 3</a></li>
                </ul>
            </div>
        </div>
        <div class="card p-4">
            <h2 class="text-center h4 fw-bold">Please fill in information</h2>
            <p class="text-center text-muted">Enter details to get going.</p>
            <form>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Full Name</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="firstName">First Name*</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="firstName" placeholder="John">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="lastName">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="lastName" placeholder="Doe">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Contact Email</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email*</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" placeholder="johndoe@gmail.com">
                            <div class="invalid-feedback">Please enter a valid email</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mobileNumber">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" class="form-control" id="mobileNumber" placeholder="(+63) 912 3456 789">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Password</label>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Modify your current password</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Enter password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password">
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
                            <input type="password" class="form-control" id="rePassword">
                            <span class="input-group-text">
                                <i class="fas fa-eye" id="toggleRePassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label class="section-title fw-bold">Position</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="role">Role</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-users"></i>
                            </span>
                            <input type="text" class="form-control" id="role" placeholder="Employee Staff">
                        </div>
                    </div>
                </div>
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
        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

        const toggleRePassword = document.querySelector('#toggleRePassword');
        const rePassword = document.querySelector('#rePassword');
        toggleRePassword.addEventListener('click', function (e) {
            const type = rePassword.getAttribute('type') === 'password' ? 'text' : 'password';
            rePassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
