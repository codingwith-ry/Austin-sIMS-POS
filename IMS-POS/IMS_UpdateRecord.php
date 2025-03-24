<?php
include("../Login/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $purchaseDate = filter_input(INPUT_POST, 'purchase_date', FILTER_SANITIZE_STRING);
    $employeeAssigned = filter_input(INPUT_POST, 'employee_assigned', FILTER_SANITIZE_STRING);

    // Debugging: Log received data
    error_log("record_id: $recordId, quantity: $quantity, purchase_date: $purchaseDate, employee_assigned: $employeeAssigned");

    if ($recordId && $quantity && $purchaseDate && $employeeAssigned) {
        try {
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
?>