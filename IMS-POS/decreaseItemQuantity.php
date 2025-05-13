<?php
include '../Login/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['confirm_decrease'])) {
    $recordID = $_POST['record_id']; // specific record
    $amountToDecrease = (int) $_POST['decrease_amount'];
    $itemName = $_POST['item_name'];
    $volume = $_POST['volume'];

    // Fetch current quantity for this specific record
    $stmt = $conn->prepare("SELECT Record_ItemQuantity FROM tbl_record_duplicate WHERE RecordDuplicate_ID = ?");
    $stmt->execute([$recordID]);
    $record = $stmt->fetch();

    if ($record && $record['Record_ItemQuantity'] >= $amountToDecrease) {
        // 1. Update the record's quantity directly
        $update = $conn->prepare("UPDATE tbl_record_duplicate SET Record_ItemQuantity = Record_ItemQuantity - ? WHERE RecordDuplicate_ID = ?");
        $update->execute([$amountToDecrease, $recordID]);

        // 2. Log in userlogs
        if (isset($_SESSION['email'], $_SESSION['userRole'])) {
            $logEmail = $_SESSION['email'];
            $logRole = $_SESSION['userRole'];
            $logContent = "Decreased quantity of Item Name: $itemName by $amountToDecrease (Volume: $volume).";
            $logDate = date('Y-m-d H:i:s');

            $logStmt = $conn->prepare("
                INSERT INTO tbl_userlogs (logEmail, logRole, logContent, logDate) 
                VALUES (:logEmail, :logRole, :logContent, :logDate)
            ");
            $logStmt->execute([
                ':logEmail' => $logEmail,
                ':logRole' => $logRole,
                ':logContent' => $logContent,
                ':logDate' => $logDate
            ]);
        }

        header("Location: Inventory_Item-Records.php");
        exit;
    } else {
        header("Location: Inventory_Item-Records.php?error=Insufficient quantity for this record.");
        exit;
    }
}
