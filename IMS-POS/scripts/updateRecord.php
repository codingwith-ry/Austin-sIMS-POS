<?php
include '../../Login/database.php';

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