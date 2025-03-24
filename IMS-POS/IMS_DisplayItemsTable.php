<?php 
include("../Login/database.php");
include("IMS_process.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Item Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

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
                <!-- <a href="IMS_EditRecord.php?record_id=<?= $row['Record_ID'] ?>" class="btn btn-warning btn-sm">Edit</a> -->
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
});
</script>

</body>
</html>