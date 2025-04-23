<?php
include '../Login/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['confirm_decrease'])) {
    $itemID = $_POST['item_id'];
    $volume = $_POST['volume'];
    $amountToDecrease = (int) $_POST['decrease_amount'];
    $itemName = $_POST['item_name'];

    // Fetch current quantity for this item+volume
    $stmt = $conn->prepare("SELECT Record_ItemQuantity, Record_ID FROM tbl_record WHERE Item_ID = ? AND Record_ItemVolume = ? ORDER BY Record_ItemQuantity DESC LIMIT 1");
    $stmt->execute([$itemID, $volume]);

    $record = $stmt->fetch();

    if ($record && $record['Record_ItemQuantity'] >= $amountToDecrease) {
        // Log the decrease in the tbl_inventory_changes table
        $insert = $conn->prepare("INSERT INTO tbl_inventory_changes (Record_ID, Change_Quantity, Change_Type) VALUES (:record_id, :change_quantity, 'decrease')");
        $insert->execute([
            ':record_id' => $record['Record_ID'],
            ':change_quantity' => $amountToDecrease
        ]);

        if (isset($_SESSION['email']) && isset($_SESSION['userRole'])) {
            $logEmail = $_SESSION['email'];
            $logRole = $_SESSION['userRole'];
            $logContent = "Decreased quantity of Item Name: $itemName by $amountToDecrease (Volume: $volume).";           
            $logDate = date('Y-m-d');

            $logStmt = $conn->prepare("
                INSERT INTO tbl_userlogs (logEmail, logRole, logContent, logDate) 
                VALUES (:logEmail, :logRole, :logContent, :logDate)
            ");
            $logStmt->bindParam(':logEmail', $logEmail);
            $logStmt->bindParam(':logRole', $logRole);
            $logStmt->bindParam(':logContent', $logContent);
            $logStmt->bindParam(':logDate', $logDate);
            $logStmt->execute();
        } else {
            error_log("Session variables 'email' or 'userRole' are not set.");
        }

        header("Location: Inventory_Item-Records.php");
        exit;
    } else {
        header("Location: Inventory_Item-Records.php?error=Insufficient quantity or item not found.");
        exit;
    }
}
