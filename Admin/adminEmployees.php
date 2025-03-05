<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Employee Management</title>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <main id="adminContent">
    <div class="row mb-2">
        <div class="col-md-6 d-flex align-items-center">
            <h1 class="mb-0">Employee Management</h1>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
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
    </div>
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <p class="text-muted mb-0">Manage and monitor employee details, roles, and activity status.</p>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <button class="btn btn-primary" id="addUserBtn" type="button">
                <i class="bi bi-person-plus"></i> Add Employee
            </button>
        </div>
    </div>

        <hr>
        <div class="table-responsive">
            <table id="employeeTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example data, replace with dynamic data as needed -->
                    <tr>
                        <td>Ryan Regulacion</td>
                        <td>Administrator</td>
                        <td>Active</td>
                        <td>2025-03-01 12:34:56</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Dominic Adino</td>
                        <td>POS Staff Management</td>
                        <td>Inactive</td>
                        <td>2025-02-28 09:21:45</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Michael Owen Trinidad</td>
                        <td>POS Staff Management</td>
                        <td>Active</td>
                        <td>2025-02-27 15:43:21</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Stephen Lance Hao</td>
                        <td>IMS Staff Management</td>
                        <td>Active</td>
                        <td>2025-02-26 08:12:34</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Daryl Tumaneng</td>
                        <td>Administrator</td>
                        <td>Active</td>
                        <td>2025-02-25 11:23:45</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Ernest John Calalin</td>
                        <td>IMS Staff Management</td>
                        <td>Active</td>
                        <td>2025-02-25 11:23:45</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Ysabella Agbanglo</td>
                        <td>IMS Staff Management</td>
                        <td>Inactive</td>
                        <td>2025-02-25 11:23:45</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                </tbody>
            </table>
        </div>
    </main>
    <?php include 'cdnScripts.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employeeTable').DataTable();
        });
    </script>
</body>
</html>

