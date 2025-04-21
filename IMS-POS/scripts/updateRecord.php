<?php
include '../../Login/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = $_POST['recordId'];
    $itemName = $_POST['itemName'];
    $itemVolume = $_POST['itemVolume'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemPrice = $_POST['itemPrice'];
    $itemExpirationDate = $_POST['itemExpirationDate'];

    try {
        // Update tbl_record
        $stmt = $conn->prepare("
            UPDATE tbl_record
            SET Record_ItemVolume = :itemVolume,
                Record_ItemQuantity = :itemQuantity,
                Record_ItemPrice = :itemPrice,
                Record_ItemExpirationDate = :itemExpirationDate
            WHERE Record_ID = :recordId
        ");
        $stmt->bindParam(':recordId', $recordId);
        $stmt->bindParam(':itemVolume', $itemVolume);
        $stmt->bindParam(':itemQuantity', $itemQuantity);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $stmt->execute();

        // Insert a log entry into tbl_userlogs
        if (isset($_SESSION['email']) && isset($_SESSION['userRole'])) {
            $logEmail = $_SESSION['email'];
            $logRole = $_SESSION['userRole'];
            $logContent = "Edited record with Record ID: $recordId. Updated fields: Volume - $itemVolume, Quantity - $itemQuantity, Price - $itemPrice, Expiration Date - $itemExpirationDate.";
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


        // Fetch the updated record
        $fetchStmt = $conn->prepare("
            SELECT 
                r.Record_ID,
                r.Record_ItemVolume,
                r.Record_ItemQuantity,
                r.Record_ItemPrice,
                r.Record_ItemExpirationDate,
                i.Item_Name,
                u.Unit_Name
            FROM tbl_record r
            LEFT JOIN tbl_item i ON r.Item_ID = i.Item_ID
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE r.Record_ID = :recordId
        ");
        $fetchStmt->bindParam(':recordId', $recordId);
        $fetchStmt->execute();
        $updatedRecord = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'updatedData' => [$updatedRecord]]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }


}
?>