<?php
include '../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $budgetToAdd = (int) $_POST['budget']; // Cast to integer for safety

    if ($budgetToAdd > 0) {
        try {
            // Check if Stock_ID = 1 exists
            $checkQuery = "SELECT COUNT(*) AS count FROM tbl_stocks WHERE Stock_ID = 1";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute();
            $rowExists = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

            if ($rowExists) {
                // Update the existing row
                $updateQuery = "UPDATE tbl_stocks SET Total_Stock_Budget = Total_Stock_Budget + :budget WHERE Stock_ID = 1";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':budget', $budgetToAdd, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Insert a new row with Stock_ID = 1 and the budget
                $insertQuery = "INSERT INTO tbl_stocks (Stock_ID, Total_Stock_Budget) VALUES (1, :budget)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':budget', $budgetToAdd, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Fetch the updated Total_Stock_Budget for Stock_ID = 1
            $fetchBudgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
            $fetchBudgetStmt = $conn->prepare($fetchBudgetQuery);
            $fetchBudgetStmt->execute();
            $totalStockBudget = $fetchBudgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

            // Insert a log entry into tbl_inventorylogs
            session_start();
            $employeeID = $_SESSION['employeeID'] ?? null; // Assuming employeeID is stored in the session
            $dateTime = date('Y-m-d H:i:s');
            $previousSum = $totalStockBudget - $budgetToAdd; // Calculate the previous budget
            $logQuery = "
                INSERT INTO tbl_inventorylogs (Employee_ID, Amount_Added, Date_Time, Previous_Sum, Stock_ID)
                VALUES (:employeeID, :amountAdded, :dateTime, :previousSum, 1)
            ";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bindParam(':employeeID', $employeeID, PDO::PARAM_STR);
            $logStmt->bindParam(':amountAdded', $budgetToAdd, PDO::PARAM_INT);
            $logStmt->bindParam(':dateTime', $dateTime, PDO::PARAM_STR);
            $logStmt->bindParam(':previousSum', $previousSum, PDO::PARAM_INT);
            $logStmt->execute();

            echo json_encode(['success' => true, 'new_budget' => $totalStockBudget]);
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