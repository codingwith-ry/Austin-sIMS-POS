<?php
// filepath: c:\xampp\htdocs\Austin-sIMS-POS\IMS-POS\updateBudget.php
include '../Login/database.php';

session_start();

date_default_timezone_set('Asia/Manila'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $budgetToAdd = number_format((float)$_POST['budget'], 2, '.', ''); // Format as decimal(11,2)

    if ($budgetToAdd > 0) {
        try {
            // Check if Stock_ID = 1 exists
            $checkQuery = "SELECT COUNT(*) AS count FROM tbl_stocks WHERE Stock_ID = 1";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute();
            $rowExists = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

            if ($rowExists) {
                // Fetch the current Total_Stock_Budget, Total_Expenses, and Total_Calculated_Budget
                $fetchStockQuery = "SELECT Total_Stock_Budget, Total_Expenses, Total_Calculated_Budget FROM tbl_stocks WHERE Stock_ID = 1";
                $fetchStockStmt = $conn->prepare($fetchStockQuery);
                $fetchStockStmt->execute();
                $stockResult = $fetchStockStmt->fetch(PDO::FETCH_ASSOC);

                $currentBudget = number_format($stockResult['Total_Stock_Budget'] ?? 0, 2, '.', '');
                $currentExpenses = number_format($stockResult['Total_Expenses'] ?? 0, 2, '.', '');
                $previousCalculatedBudget = number_format($stockResult['Total_Calculated_Budget'] ?? 0, 2, '.', '');

                // Update the Total_Stock_Budget
                $newBudget = number_format($currentBudget + $budgetToAdd, 2, '.', '');
                $newCalculatedBudget = number_format($newBudget - $currentExpenses, 2, '.', '');

                $updateQuery = "
                    UPDATE tbl_stocks 
                    SET Total_Stock_Budget = :newBudget, Total_Calculated_Budget = :newCalculatedBudget 
                    WHERE Stock_ID = 1
                ";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':newBudget', $newBudget, PDO::PARAM_STR);
                $updateStmt->bindParam(':newCalculatedBudget', $newCalculatedBudget, PDO::PARAM_STR);
                $updateStmt->execute();
            } else {
                // Insert a new row with Stock_ID = 1 and the budget
                $newCalculatedBudget = $budgetToAdd; // No expenses yet
                $insertQuery = "
                    INSERT INTO tbl_stocks (Stock_ID, Total_Stock_Budget, Total_Expenses, Total_Calculated_Budget) 
                    VALUES (1, :budget, 0, :calculatedBudget)
                ";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':budget', $budgetToAdd, PDO::PARAM_STR);
                $insertStmt->bindParam(':calculatedBudget', $newCalculatedBudget, PDO::PARAM_STR);
                $insertStmt->execute();

                $currentBudget = 0; // No previous budget
                $previousCalculatedBudget = 0; // No previous calculated budget
            }

            // Insert a log entry into tbl_inventorylogs
            $employeeID = $_SESSION['employeeID'] ?? null; // Assuming employeeID is stored in the session
            $dateTime = date('Y-m-d H:i:s');
            $previousSum = $previousCalculatedBudget; // Previous Total_Calculated_Budget
            $updatedSum = $newCalculatedBudget; // New Total_Calculated_Budget

            $logQuery = "
                INSERT INTO tbl_inventorylogs (Employee_ID, Amount_Added, Date_Time, Previous_Sum, Stock_ID, Updated_Sum)
                VALUES (:employeeID, :amountAdded, :dateTime, :previousSum, 1, :updatedSum)
            ";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bindParam(':employeeID', $employeeID, PDO::PARAM_STR);
            $logStmt->bindParam(':amountAdded', $budgetToAdd, PDO::PARAM_STR);
            $logStmt->bindParam(':dateTime', $dateTime, PDO::PARAM_STR);
            $logStmt->bindParam(':previousSum', $previousSum, PDO::PARAM_STR);
            $logStmt->bindParam(':updatedSum', $updatedSum, PDO::PARAM_STR);
            $logStmt->execute();

            echo json_encode(['success' => true, 'new_budget' => $newBudget]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid budget amount.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>