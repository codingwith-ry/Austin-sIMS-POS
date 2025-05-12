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

    if (!empty($recordIds) && is_array($recordIds)) {
        try {
            // Prepare placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($recordIds), '?'));

            // Fetch the total price of the records to be deleted
            $fetchPricesStmt = $conn->prepare("SELECT SUM(Record_TotalPrice) AS totalPrice FROM tbl_record WHERE Record_ID IN ($placeholders)");
            $fetchPricesStmt->execute($recordIds);
            $totalPriceToDelete = number_format($fetchPricesStmt->fetch(PDO::FETCH_ASSOC)['totalPrice'] ?? 0, 2, '.', '');

            // Fetch the current Total_Stock_Budget, Total_Expenses, and Total_Calculated_Budget from tbl_stocks
            $stockID = 1; // Assuming Stock_ID is 1 (adjust as needed)
            $stockQuery = $conn->prepare("SELECT Total_Stock_Budget, Total_Expenses, Total_Calculated_Budget FROM tbl_stocks WHERE Stock_ID = :stockID");
            $stockQuery->bindParam(':stockID', $stockID);
            $stockQuery->execute();
            $stockResult = $stockQuery->fetch(PDO::FETCH_ASSOC);

            $totalStockBudget = number_format($stockResult['Total_Stock_Budget'] ?? 0, 2, '.', '');
            $currentTotalExpenses = number_format($stockResult['Total_Expenses'] ?? 0, 2, '.', '');
            $previousCalculatedBudget = number_format($stockResult['Total_Calculated_Budget'] ?? 0, 2, '.', '');

            // Calculate the new Total_Expenses and Total_Calculated_Budget
            $newTotalExpenses = number_format($currentTotalExpenses - $totalPriceToDelete, 2, '.', '');
            $newCalculatedBudget = number_format($totalStockBudget - $newTotalExpenses, 2, '.', '');

            // Update the Total_Expenses and Total_Calculated_Budget in tbl_stocks
            $updateStockStmt = $conn->prepare("
                UPDATE tbl_stocks 
                SET Total_Expenses = :newTotalExpenses, Total_Calculated_Budget = :newCalculatedBudget 
                WHERE Stock_ID = :stockID
            ");
            $updateStockStmt->bindParam(':newTotalExpenses', $newTotalExpenses);
            $updateStockStmt->bindParam(':newCalculatedBudget', $newCalculatedBudget);
            $updateStockStmt->bindParam(':stockID', $stockID);
            $updateStockStmt->execute();

            // Delete related rows in tbl_inventory_changes
            $deleteChangesStmt = $conn->prepare("DELETE FROM tbl_inventory_changes WHERE Record_ID IN ($placeholders)");
            $deleteChangesStmt->execute($recordIds);

            // Delete the records in tbl_record
            $deleteRecordStmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID IN ($placeholders)");
            $deleteRecordStmt->execute($recordIds);

            // Delete the records in tbl_record_duplicate
            $deleteDuplicateRecordStmt = $conn->prepare("DELETE FROM tbl_record_duplicate WHERE RecordDuplicate_ID IN ($placeholders)");
            $deleteDuplicateRecordStmt->execute($recordIds);

            // Insert an entry into tbl_inventorylogs
            $amountAdded = number_format($totalPriceToDelete, 2, '.', ''); // Positive because it's a deletion
            $dateTime = date('Y-m-d H:i:s'); // Current date and time

            $inventoryLogStmt = $conn->prepare("
                INSERT INTO tbl_inventorylogs (Employee_ID, Amount_Added, Date_Time, Previous_Sum, Stock_ID, Updated_Sum)
                VALUES (:employeeID, :amountAdded, :dateTime, :previousSum, :stockID, :updatedSum)
            ");
            $inventoryLogStmt->bindParam(':employeeID', $_SESSION['employeeID']); // Assuming Employee_ID is stored in the session
            $inventoryLogStmt->bindParam(':amountAdded', $amountAdded);
            $inventoryLogStmt->bindParam(':dateTime', $dateTime);
            $inventoryLogStmt->bindParam(':previousSum', $previousCalculatedBudget); // Previous Total_Calculated_Budget
            $inventoryLogStmt->bindParam(':stockID', $stockID);
            $inventoryLogStmt->bindParam(':updatedSum', $newCalculatedBudget); // New Total_Calculated_Budget
            $inventoryLogStmt->execute();

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