<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'administrator') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}

include("../Login/database.php");
?>

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
            <a href="/Austin-sIMS-POS/Admin/adminAddUser.php">
                <button class="btn btn-primary" id="addUserBtn" type="button">
                    <i class="bi bi-person-plus"></i> Add Employee
                </button>
            </a>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT Employee_ID, Employee_Name, Employee_Role, Employee_Status FROM employees");
                        $stmt->execute();
                        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($employees as $employee) {
                            echo "<tr data-id='" . $employee['Employee_ID'] . "'>";
                            echo "<td class='editable' data-field='Employee_Name'>" . htmlspecialchars($employee['Employee_Name']) . "</td>";
                            echo "<td class='editable' data-field='Employee_Role'>" . htmlspecialchars($employee['Employee_Role']) . "</td>";
                            echo "<td class='editable' data-field='Employee_Status'>" . htmlspecialchars($employee['Employee_Status']) . "</td>";
                            echo "<td>
                                    <button class='btn btn-sm btn-outline-secondary edit-btn' type='button'>
                                        <i class='bi bi-pencil'></i> Edit
                                    </button>
                                    <button class='btn btn-sm btn-outline-danger delete-btn' type='button'>
                                        <i class='bi bi-trash'></i> Delete
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
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

            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                row.find('.editable').each(function() {
                    const field = $(this).data('field');
                    const value = $(this).text();
                    $(this).html(`<input type="text" class="form-control" name="${field}" value="${value}">`);
                });

                $(this).removeClass('edit-btn btn-outline-secondary').addClass('save-btn btn-outline-success').html('<i class="bi bi-check"></i> Save');
            });

            // Handle save button click
            $(document).on('click', '.save-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const data = {};

                row.find('input').each(function() {
                    const field = $(this).attr('name');
                    const value = $(this).val();
                    data[field] = value;
                });

                $.ajax({
                    url: 'updateEmployee.php',
                    type: 'POST',
                    data: { id: id, ...data },
                    success: function(response) {
                        row.find('.editable').each(function() {
                            const field = $(this).data('field');
                            $(this).text(data[field]);
                        });

                        row.find('.save-btn').removeClass('save-btn btn-outline-success').addClass('edit-btn btn-outline-secondary').html('<i class="bi bi-pencil"></i> Edit');
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating employee: ' + error);
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                if (confirm('Are you sure you want to delete this employee?')) {
                    $.ajax({
                        url: 'deleteEmployee.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.success) {
                                row.remove();
                            } else {
                                alert('Error deleting employee: ' + res.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error deleting employee: ' + error);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>