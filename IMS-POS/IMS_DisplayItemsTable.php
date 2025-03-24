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
                <a href="IMS_EditRecord.php?record_id=<?= $row['Record_ID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="IMS_DeleteRecord.php?record_id=<?= $row['Record_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
    <div class="alert alert-info">No records found for this item.</div>
<?php endif; ?>

</body>
</html>