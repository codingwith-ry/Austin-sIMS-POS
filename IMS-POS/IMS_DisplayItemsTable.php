<?php 
include("../Login/database.php");
include("IMS_process.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Item Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="p-4">
<?php include 'verticalNav.php' ?>
    <main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
<h3>Item Records</h3>

<?php if (!empty($records)) : ?>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Purchase Date</th>
            <th>Employee Assigned</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['Item_Name']) ?></td>
            <td><?= htmlspecialchars($row['Record_ItemQuantity']) ?> pcs</td>
            <td><?= htmlspecialchars($row['Record_ItemPurchaseDate']) ?></td>
            <td><?= htmlspecialchars($row['Employee_Name'] ?? 'N/A') ?></td>
            <td>
            <a href="#" class="btn btn-warning btn-sm edit-btn" data-record-id="<?= $row['Record_ID'] ?>">Edit</a>
                <a href="#" class="btn btn-danger btn-sm delete-btn" data-record-id="<?= $row['Record_ID'] ?>">Delete</a>
                <!-- <a href="IMS_DeleteRecord.php?record_id=<?= $row['Record_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a> -->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
    <div class="alert alert-info">No records found for this item.</div>
<?php endif; ?>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle Delete button click
    document.querySelectorAll('.delete-btn').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior

            const recordId = this.getAttribute('data-record-id'); // Get the Record_ID
            const row = this.closest('tr'); // Get the row containing the button

            // Confirm deletion
            if (confirm('Are you sure you want to delete this record?')) {
                // Send AJAX request to delete the record
                fetch('IMS_DeleteRecord.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `record_id=${recordId}`,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        row.remove();
                        alert('Record deleted successfully!');
                    } else {
                        alert('Failed to delete record: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the record.');
                });
            }
        });
    });

    // Handle Edit button click
    document.querySelectorAll('.btn-warning').forEach(function (button) {
    button.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        // Populate the modal with the record data
        const recordId = this.getAttribute('data-record-id');
        const itemName = this.closest('tr').querySelector('td:nth-child(1)').textContent.trim();
        const quantity = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim().replace(' pcs', '');
        const purchaseDate = this.closest('tr').querySelector('td:nth-child(3)').textContent.trim();
        const employeeName = this.closest('tr').querySelector('td:nth-child(4)').textContent.trim();

        document.getElementById('editRecordId').value = recordId;
        document.getElementById('editItemName').value = itemName;
        document.getElementById('editQuantity').value = quantity;
        document.getElementById('editPurchaseDate').value = purchaseDate;

        // Set the selected employee in the dropdown
        const employeeDropdown = document.getElementById('editEmployeeName');
        Array.from(employeeDropdown.options).forEach(option => {
            if (option.textContent.trim() === employeeName) {
                option.selected = true;
            } else {
                option.selected = false;
            }
        });

        // Show the modal
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });
});

    // Handle Save Changes button click
    document.getElementById('saveEditRecord').addEventListener('click', function () {
        const recordId = document.getElementById('editRecordId').value;
        const quantity = document.getElementById('editQuantity').value;
        const purchaseDate = document.getElementById('editPurchaseDate').value;
        const employeeAssigned = document.getElementById('editEmployeeName').value;

        console.log('Record ID:', recordId);
    console.log('Quantity:', quantity);
    console.log('Purchase Date:', purchaseDate);
    console.log('Employee Assigned:', employeeAssigned);

        if (!recordId || !quantity || !purchaseDate || !employeeAssigned) {
            alert('Please fill in all required fields.');
            return;
        }

        const formData = new FormData(document.getElementById('editRecordForm'));

        fetch('IMS_UpdateRecord.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Record updated successfully!');
                window.location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to update record: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the record.');
        });
    });
});
</script>

<!-- Edit Record Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRecordForm">
                    <input type="hidden" id="editRecordId" name="record_id">
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="editItemName" name="item_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity">
                    </div>
                    <div class="mb-3">
                        <label for="editPurchaseDate" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="editPurchaseDate" name="purchase_date">
                    </div>
                    <div class="mb-3">
                        <label for="editEmployeeName" class="form-label">Employee Assigned</label>
                        <select class="form-select" id="editEmployeeName" name="employee_assigned">
                            <option value="" disabled selected>Select Employee</option>
                            <?php
                            // Fetch employees from the database
                            $stmt = $conn->prepare("SELECT Employee_ID, Employee_Name FROM employees");
                            $stmt->execute();
                            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($employees as $employee) {
                                echo '<option value="' . htmlspecialchars($employee['Employee_ID']) . '">' . htmlspecialchars($employee['Employee_Name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditRecord">Save Changes</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>