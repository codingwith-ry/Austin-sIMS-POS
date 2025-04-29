<?php
include("../Login/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_SANITIZE_NUMBER_INT);

    if ($recordId) {
        try {
            // Delete related rows in tbl_inventory_changes
            $deleteChangesStmt = $conn->prepare("DELETE FROM tbl_inventory_changes WHERE Record_ID = :recordId");
            $deleteChangesStmt->bindParam(':recordId', $recordId);
            $deleteChangesStmt->execute();

            // Delete the record in tbl_record
            $deleteRecordStmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID = :recordId");
            $deleteRecordStmt->bindParam(':recordId', $recordId);
            $deleteRecordStmt->execute();

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid record ID.']);
    }
}
?>