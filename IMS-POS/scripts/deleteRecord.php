<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../Login/database.php';

date_default_timezone_set('Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordIds = $_POST['recordIds']; // Array of record IDs to delete

    // Debugging: Log received record IDs
    error_log("Received recordIds: " . print_r($recordIds, true));

    if (!empty($recordIds) && is_array($recordIds)) {
        try {
            // Prepare placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($recordIds), '?'));

            // Delete related rows in tbl_inventory_changes
            $deleteChangesStmt = $conn->prepare("DELETE FROM tbl_inventory_changes WHERE Record_ID IN ($placeholders)");
            $deleteChangesStmt->execute($recordIds);

            // Delete the records in tbl_record
            $deleteRecordStmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID IN ($placeholders)");
            $deleteRecordStmt->execute($recordIds);

            // Log the deletion in tbl_userlogs
            if (isset($_SESSION['email']) && isset($_SESSION['userRole'])) {
                $logEmail = $_SESSION['email'];
                $logRole = $_SESSION['userRole'];
                $logContent = "Deleted record(s) with Record ID(s): " . implode(', ', $recordIds);
                $logDate = date('Y-m-d H:i:s');

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

            // Return success response
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Return error response
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid record IDs']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>