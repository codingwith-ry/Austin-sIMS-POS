<?php

session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'inventory staff management') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}
$active = "Inventory_Item-Records";

include("../Login/database.php");
include("IMS_process.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Item Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <?php require_once('links.php'); ?>
    <?php require('IMS_process.php') ?>
</head>

<body class="p-4">
    <?php include 'verticalNav.php' ?>
    <main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
        <h3>Item Records</h3>

        <?php if (!empty($records)) : ?>
            <table id="recordTable" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th></th> <!-- For expand button -->
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Purchase Date</th>
                        <th>Employee Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                        <?php
                        $recordID = $row['RecordDuplicate_ID'];
                        $itemName = htmlspecialchars($row['Item_Name']);
                        $itemQty = (int)$row['Record_ItemQuantity'];
                        $modalID = 'decreaseModal_' . $recordID;
                        $uniqueKey = $recordID; // Unique per record
                        $lowStockThreshold = 10; // Change as needed
                        $itemVolume = $_GET['volume'] ?? ''; // Retrieved from query param
                        ?>
                        <tr data-record-id="<?= $recordID ?>" data-expiration="<?= htmlspecialchars($row['Record_ItemExpirationDate']) ?>">
                            <td class="details-control"></td>
                            <td><?= $itemName ?></td>
                            <td><?= $itemQty ?> pcs</td>
                            <td><?= htmlspecialchars($row['Record_ItemPurchaseDate']) ?></td>
                            <td><?= htmlspecialchars($row['Employee_Name'] ?? 'N/A') ?></td>
                            <td>
                                <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#<?= $modalID ?>">
                                    Decrease Quantity
                                </button>

                                <!-- Decrease Quantity Modal -->
                                <div class="modal fade" id="<?= $modalID ?>" tabindex="-1" aria-labelledby="<?= $modalID ?>Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post" action="decreaseItemQuantity.php">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title" id="<?= $modalID ?>Label">Decrease Quantity</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong><?= $itemName ?></strong></p>
                                                    <p>Available Quantity: <strong><?= $itemQty ?> pcs</strong></p>
                                                    <p>Low Stock Threshold: <strong><?= $lowStockThreshold ?> pcs</strong></p>
                                                    <div class="mb-3">
                                                        <label for="slider_<?= $uniqueKey ?>" class="form-label">Select amount to decrease:</label>
                                                        <input type="range" class="form-range" min="0" max="<?= $itemQty ?>" value="1" id="slider_<?= $uniqueKey ?>" name="decrease_amount" oninput="updatePreview_<?= $uniqueKey ?>()">
                                                        <div>Decreasing by: <strong id="decreasePreview_<?= $uniqueKey ?>">1</strong> pcs</div>
                                                        <div>Quantity after decrease: <strong id="afterQty_<?= $uniqueKey ?>"><?= $itemQty - 1 ?></strong> pcs</div>
                                                    </div>
                                                    <input type="hidden" name="record_id" value="<?= $recordID ?>">
                                                    <input type="hidden" name="item_name" value="<?= $itemName ?>">
                                                    <input type="hidden" name="volume" value="<?= htmlspecialchars($itemVolume) ?>">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="confirm_decrease" class="btn btn-danger">Confirm Decrease</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Inline JavaScript for this row -->
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const slider = document.getElementById("slider_<?= $uniqueKey ?>");
                                        const preview = document.getElementById("decreasePreview_<?= $uniqueKey ?>");
                                        const afterQty = document.getElementById("afterQty_<?= $uniqueKey ?>");
                                        const currentQty = <?= $itemQty ?>;

                                        if (slider && preview && afterQty) {
                                            slider.addEventListener("input", function() {
                                                const decreaseVal = parseInt(slider.value);
                                                preview.textContent = decreaseVal;
                                                afterQty.textContent = currentQty - decreaseVal;
                                            });

                                            const modal = document.getElementById("<?= $modalID ?>");
                                            if (modal) {
                                                modal.addEventListener("shown.bs.modal", function() {
                                                    const decreaseVal = parseInt(slider.value);
                                                    preview.textContent = decreaseVal;
                                                    afterQty.textContent = currentQty - decreaseVal;
                                                });
                                            }
                                        }
                                    });
                                </script>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php else : ?>
            <div class="alert alert-info">No records found for this item.</div>
        <?php endif; ?>


    </main>

    <?php include "footer.php" ?>
    <script src="scripts/displayItemData.js"></script>
</body>

</html>