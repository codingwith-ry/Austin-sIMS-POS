<?php
include("../Login/database.php");
session_start();
date_default_timezone_set('Asia/Manila'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'Employee_Name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'Employee_Email', FILTER_SANITIZE_EMAIL); // New email field
    $role = filter_input(INPUT_POST, 'Employee_Role', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'Employee_Status', FILTER_SANITIZE_STRING);

    if ($id && $name && $email && $role && $status) {
        try {
            $stmt = $conn->prepare("UPDATE employees SET Employee_Name = :name, Employee_Email = :email, Employee_Role = :role, Employee_Status = :status WHERE Employee_ID = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email); // Bind email
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            if (isset($_SESSION['email']) && isset($_SESSION['userRole'])) {
                $logEmail = $_SESSION['email'];
                $logRole = $_SESSION['userRole'];
                $logContent = "Edited employee with ID: $id. Updated fields: Name - $name, Email - $email, Role - $role, Status - $status.";
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

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update employee']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}
?>