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
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3">
                <!-- Notification Dropdown -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary position-relative dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 300px; overflow-y: auto;">
                        <li><strong class="dropdown-header">Notifications</strong></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">🛒 New order received</a></li>
                        <li><a class="dropdown-item" href="#">📦 Inventory stock low</a></li>
                        <li><a class="dropdown-item" href="#">👤 New employee registered</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul>
                </div>

                <!-- Administrator Dropdown -->
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
                        <th>Email</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT Employee_ID, Employee_Name, Employee_Role, Employee_Status, Employee_Email FROM employees");
                        $stmt->execute();
                        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($employees as $employee) {
                            echo "<tr data-id='" . $employee['Employee_ID'] . "'>";
                            echo "<td class='editable' data-field='Employee_Name'>" . htmlspecialchars($employee['Employee_Name']) . "</td>";
                            echo "<td class='editable' data-field='Employee_Email'>" . htmlspecialchars($employee['Employee_Email']) . "</td>"; // New email field
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
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <input type="date" id="logDateInput" class="form-control w-auto me-2">
        <button id="searchByDateBtn" class="btn btn-primary">Search by Date</button>
    </div>
    <div>
        <button id="printLogsBtn" class="btn btn-secondary"><i class="bi bi-printer"></i> Print</button>
    </div>
</div>



            <table id="userLogsTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Log ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Content</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <!-- Logs will be dynamically populated here -->
    </tbody>
</table>
            <div id="paginationControls" class="mt-3 d-flex justify-content-center"></div>
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

                row.find('.editable').each(function() {
                    const field = $(this).data('field');
                    const value = $(this).text();

                    if (field === 'Employee_Role') {
                        // Dropdown for Position
                        $(this).html(`
                    <select class="form-control" name="${field}">
                        <option value="Inventory Staff Management" ${value === 'Inventory Staff Management' ? 'selected' : ''}>Inventory Staff Management</option>
                        <option value="Administrator" ${value === 'Administrator' ? 'selected' : ''}>Administrator</option>
                        <option value="POS Staff Management" ${value === 'POS Staff Management' ? 'selected' : ''}>POS Staff Management</option>
                    </select>
                `);
                    } else if (field === 'Employee_Status') {
                        // Dropdown for Status
                        $(this).html(`
                    <select class="form-control" name="${field}">
                        <option value="Active" ${value === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="Inactive" ${value === 'Inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                `);
                    } else {
                        // Default input field for other fields (including email)
                        $(this).html(`<input type="text" class="form-control" name="${field}" value="${value}">`);
                    }
                });

                $(this).removeClass('edit-btn btn-outline-secondary').addClass('save-btn btn-outline-success').html('<i class="bi bi-check"></i> Save');
            });

            // Handle save button click
            $(document).on('click', '.save-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const data = {};

                row.find('input, select').each(function() {
                    const field = $(this).attr('name');
                    const value = $(this).val();
                    data[field] = value;
                });

                $.ajax({
                    url: 'updateEmployee.php',
                    type: 'POST',
                    data: {
                        id: id,
                        ...data
                    },
                    success: function(response) {
                        row.find('.editable').each(function() {
                            const field = $(this).data('field');
                            $(this).text(data[field]);
                        });

                        row.find('.save-btn').removeClass('save-btn btn-outline-success').addClass('edit-btn btn-outline-secondary').html('<i class="bi bi-pencil"></i> Edit');
                        window.location.reload();
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
                        data: {
                            id: id
                        },
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

    <script>  
$(document).ready(function () {
    const logsPerPage = 10;

    // Function to fetch logs
    function fetchLogs(page = 1, logDate = null) {
        $.ajax({
            url: 'fetchUserLogs.php',
            type: 'POST',
            data: { page: page, logDate: logDate },
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    const logs = res.logs;
                    const totalLogs = res.totalLogs;

                    // Populate the logs table
                    const tbody = $('#userLogsTable tbody');
                    tbody.empty();
                    logs.forEach(log => {
                        tbody.append(`
                            <tr>
                                <td>${log.logID}</td>
                                <td>${log.logEmail}</td>
                                <td>${log.logRole}</td>
                                <td>${log.logContent}</td>
                                <td>${log.logDate}</td>
                            </tr>
                        `);
                    });

                    // Update pagination controls
                    updatePaginationControls(page, totalLogs, logsPerPage);
                } else {
                    alert('Failed to fetch logs: ' + res.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Error fetching logs: ' + error);
            }
        });
    }

    // Function to update pagination controls
    function updatePaginationControls(currentPage, totalLogs, logsPerPage) {
        const totalPages = Math.ceil(totalLogs / logsPerPage);
        const paginationControls = $('#paginationControls');
        paginationControls.empty();

        for (let i = 1; i <= totalPages; i++) {
            const button = $(`<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} mx-1">${i}</button>`);
            button.on('click', function () {
                const logDate = $('#logDateInput').val(); // Get the selected date
                fetchLogs(i, logDate);
            });
            paginationControls.append(button);
        }
    }

    // Handle search by date
    $('#searchByDateBtn').on('click', function () {
        const logDate = $('#logDateInput').val();
        if (logDate) {
            fetchLogs(1, logDate); // Fetch logs for the selected date
        } else {
            alert('Please select a date to search.');
        }
    });

    // Initial fetch
    fetchLogs();
});

    </script>
    <script>
document.getElementById('printLogsBtn').addEventListener('click', function () {
    const printContent = document.getElementById('userLogsTable').outerHTML;
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Print Logs</title></head><body>');
    printWindow.document.write('<h1>Employee Logs</h1>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
});
    </script>
</body>

</html>