<?php
// filepath: c:\xampp\htdocs\Austin-sIMS-POS\IMS-POS\scripts\updateRecord.php
include '../../Login/database.php';

session_start();

date_default_timezone_set('Asia/Manila'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recordId = $_POST['recordId'];
    $itemVolume = $_POST['itemVolume'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemPrice = $_POST['itemPrice'];
    $itemExpirationDate = $_POST['itemExpirationDate']; // Fetch expiration date from input

    try {
        // Update the record in tbl_record
        $updateStmt = $conn->prepare("
            UPDATE tbl_record
            SET 
                Record_ItemVolume = :itemVolume,
                Record_ItemQuantity = :itemQuantity,
                Record_ItemPrice = :itemPrice,
                Record_ItemExpirationDate = :itemExpirationDate
            WHERE Record_ID = :recordId
        ");

        $updateStmt->bindParam(':recordId', $recordId);
        $updateStmt->bindParam(':itemVolume', $itemVolume);
        $updateStmt->bindParam(':itemQuantity', $itemQuantity);
        $updateStmt->bindParam(':itemPrice', $itemPrice);
        $updateStmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $updateStmt->execute();

        // Fetch the updated record
        $fetchStmt = $conn->prepare("
            SELECT 
                r.Record_ID,
                r.Record_ItemVolume,
                r.Record_ItemQuantity,
                r.Record_ItemPrice,
                r.Record_ItemExpirationDate,
                u.Unit_Name
            FROM tbl_record r
            LEFT JOIN tbl_item i ON r.Item_ID = i.Item_ID
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE r.Record_ID = :recordId
        ");

        $fetchStmt->bindParam(':recordId', $recordId);
        $fetchStmt->execute();

        $updatedRecord = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'record' => $updatedRecord]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>