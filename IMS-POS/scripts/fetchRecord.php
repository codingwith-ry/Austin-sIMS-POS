<?php
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = $_POST['recordId'];

    try {
        // Fetch the details of the current record
        $stmt = $conn->prepare("
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
        $stmt->bindParam(':recordId', $recordId);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch only the item associated with the record
        $itemsStmt = $conn->prepare("
            SELECT 
                i.Item_ID,
                i.Item_Name,
                u.Unit_Name
            FROM tbl_item i
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE i.Item_ID = (
                SELECT Item_ID FROM tbl_record WHERE Record_ID = :recordId
            )
        ");
        $itemsStmt->bindParam(':recordId', $recordId);
        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($record) {
            echo json_encode(['success' => true, 'record' => $record, 'items' => $items]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>