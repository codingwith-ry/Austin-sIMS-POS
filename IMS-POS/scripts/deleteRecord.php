<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordIds = $_POST['recordIds'];

    // Debugging: Log received recordIds
    error_log("Received recordIds: " . print_r($recordIds, true));

    if (!empty($recordIds) && is_array($recordIds)) {
        try {
            $placeholders = implode(',', array_fill(0, count($recordIds), '?'));
            $stmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID IN ($placeholders)");
            $stmt->execute($recordIds);

            // Fetch updated data
            $fetchStmt = $conn->query("SELECT * FROM tbl_record");
            $updatedData = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'updatedData' => $updatedData]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid record IDs']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>