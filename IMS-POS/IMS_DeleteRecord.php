<?php
include("../Login/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_SANITIZE_NUMBER_INT);

    if ($recordId) {
        try {
            $stmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID = :recordId");
            $stmt->bindParam(':recordId', $recordId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete record.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid record ID.']);
    }
}
?>