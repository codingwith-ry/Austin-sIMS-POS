<?php
include("../Login/database.php");
session_start();
date_default_timezone_set('Asia/Manila'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        try {
            // Fetch employee details for logging
            $fetchStmt = $conn->prepare("SELECT Employee_Name, Employee_Email FROM employees WHERE Employee_ID = :id");
            $fetchStmt->bindParam(':id', $id);
            $fetchStmt->execute();
            $employee = $fetchStmt->fetch(PDO::FETCH_ASSOC);

            if ($employee) {
                $stmt = $conn->prepare("DELETE FROM employees WHERE Employee_ID = :id");
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    // Add log entry
                    $logEmail = $_SESSION['email'];
                    $logRole = $_SESSION['userRole'];
                    $logContent = "Deleted employee: " . $employee['Employee_Name'] . " (Email: " . $employee['Employee_Email'] . ").";
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

                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete employee']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Employee not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid employee ID']);
    }
}
?>