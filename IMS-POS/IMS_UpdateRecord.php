<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $purchaseDate = filter_input(INPUT_POST, 'purchase_date', FILTER_SANITIZE_STRING);
    $employeeAssigned = filter_input(INPUT_POST, 'employee_assigned', FILTER_SANITIZE_STRING);

    // Debugging: Log received data
    error_log("record_id: $recordId, quantity: $quantity, purchase_date: $purchaseDate, employee_assigned: $employeeAssigned");

    if ($recordId && $quantity && $purchaseDate && $employeeAssigned) {
        try {
            // Update the record in tbl_record
            $stmt = $conn->prepare("
                UPDATE tbl_record 
                SET 
                    Record_ItemQuantity = :quantity, 
                    Record_ItemPurchaseDate = :purchaseDate, 
                    Record_EmployeeAssigned = :employeeAssigned 
                WHERE Record_ID = :recordId
            ");
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':purchaseDate', $purchaseDate);
            $stmt->bindParam(':employeeAssigned', $employeeAssigned);
            $stmt->bindParam(':recordId', $recordId);

            if ($stmt->execute()) {
                // Check if session variables are set
                if (!isset($_SESSION['email']) || !isset($_SESSION['userRole'])) {
                    error_log("Session variables 'email' or 'userRole' are not set.");
                    echo json_encode(['success' => false, 'message' => 'Session variables are not set.']);
                    exit();
                }

                // Log the update in tbl_userlogs
                $logEmail = $_SESSION['email'];
                $logRole = $_SESSION['userRole'];
                $logContent = "Edited record with Record ID: $recordId. Updated fields: Quantity - $quantity, Purchase Date - $purchaseDate, Assigned Employee - $employeeAssigned.";
                $logDate = date('Y-m-d');

                try {
                    $logStmt = $conn->prepare("
                        INSERT INTO tbl_userlogs (logEmail, logRole, logContent, logDate) 
                        VALUES (:logEmail, :logRole, :logContent, :logDate)
                    ");
                    $logStmt->bindParam(':logEmail', $logEmail);
                    $logStmt->bindParam(':logRole', $logRole);
                    $logStmt->bindParam(':logContent', $logContent);
                    $logStmt->bindParam(':logDate', $logDate);

                    if (!$logStmt->execute()) {
                        error_log("Failed to insert log entry: " . implode(", ", $logStmt->errorInfo()));
                    }
                } catch (PDOException $e) {
                    error_log("Error inserting log entry: " . $e->getMessage());
                }

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update record']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}