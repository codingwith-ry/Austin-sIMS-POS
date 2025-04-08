<?php

include '../Login/database.php';

if (isset($_POST['confirm_decrease'])) {
    require '../Login/database.php'; // your PDO connection file

    $item_id = $_POST['item_id'];
    $decrease_amount = intval($_POST['decrease_amount']);

    // Fetch current quantity
    $stmt = $conn->prepare("SELECT Record_ItemQuantity FROM tbl_record WHERE Item_ID = ?");
    $stmt->execute([$item_id]);
    $record = $stmt->fetch();

    if ($record && $record['Record_ItemQuantity'] >= $decrease_amount) {
        $newQty = $record['Record_ItemQuantity'] - $decrease_amount;

        $update = $conn->prepare("UPDATE tbl_record SET Record_ItemQuantity = ? WHERE Item_ID = ?");
        $update->execute([$newQty, $item_id]);

        header("Location: Inventory_Item-Records.php");
        exit;
    } else {
        header("Location: your_items_page.php?error=not_enough_quantity");
        exit;
    }
}
